import sys
import cv2
import face_recognition
import numpy as np
import mysql.connector
import pickle
import time
import datetime

# Check if course_id and section_id are provided
if len(sys.argv) < 3:
    print("Usage: python take_attendance.py <course_id> <section_id>")
    sys.exit(1)

course_id = sys.argv[1]
section_id = sys.argv[2]

# MySQL Database Configuration
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "attendancesystem"
}

def create_connection():
    return mysql.connector.connect(**DB_CONFIG)

conn = create_connection()
cursor = conn.cursor()

# Get current date
current_date = datetime.date.today()

# Get the list of students enrolled in the given course and section
cursor.execute("SELECT sid FROM classroom WHERE course_id = %s AND section_id = %s", (course_id, section_id))
enrolled_students = {row[0] for row in cursor.fetchall()}

# Dictionary to store encodings
known_face_encodings = []
known_face_sids = []

# Retrieve encoded faces from database for enrolled students
cursor.execute("SELECT sid, face FROM student WHERE sid IN (%s)" % ",".join(map(str, enrolled_students)))
for sid, face_blob in cursor.fetchall():
    if face_blob:
        face_encoding = pickle.loads(face_blob)
        known_face_encodings.append(face_encoding)
        known_face_sids.append(sid)

cursor.close()
conn.close()

student_detection_counts = {sid: 0 for sid in known_face_sids}
students_saved = set()

video_capture = cv2.VideoCapture(0)
frame_skip = 10
frame_count = 0
last_check_time = time.time()
detection_threshold = 8
start_time = time.time()

while True:
    ret, frame = video_capture.read()
    if not ret:
        break

    elapsed_time = time.time() - start_time
    if elapsed_time > 20:
        print("Class time ended. Closing the program.")
        break

    if time.time() - last_check_time >= 2:
        last_check_time = time.time()
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_frame)
        face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

        recognized_this_frame = set()

        for face_encoding, face_location in zip(face_encodings, face_locations):
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances) if face_distances.size > 0 else None

            if best_match_index is not None and matches[best_match_index]:
                sid = known_face_sids[best_match_index]
                if sid in enrolled_students:  # Ensure student is enrolled in this course and section
                    recognized_this_frame.add(sid)
                    # Modify the print statement to include only the current time
                    print(f"Student {sid} was present at {datetime.datetime.now().strftime('%H:%M:%S')}")


                    student_detection_counts[sid] += 1

                    if student_detection_counts[sid] >= detection_threshold and sid not in students_saved:
                        conn = create_connection()
                        cursor = conn.cursor()
                        try:
                            cursor.execute("SELECT COUNT(*) FROM attendance WHERE sid = %s AND course_id = %s AND section_id = %s AND date = %s", (sid, course_id, section_id, current_date))
                            if cursor.fetchone()[0] == 0:
                                cursor.execute("INSERT INTO attendance (date, sid, course_id, section_id) VALUES (%s, %s, %s, %s)", (current_date, sid, course_id, section_id))
                                conn.commit()
                                print(f"Student {sid} marked present.")
                                students_saved.add(sid)
                            
                        except mysql.connector.Error as e:
                            print(f"Database error: {e}")
                        finally:
                            cursor.close()
                            conn.close()

            top, right, bottom, left = face_location
            cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
            cv2.putText(frame, str(sid), (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)
            
    # Function to calculate the position for center alignment
    def get_center_position(text, frame_width, font, font_scale, thickness):
        text_size = cv2.getTextSize(text, font, font_scale, thickness)[0]
        text_width = text_size[0]
        x = (frame_width - text_width) // 2  # Calculate the x-coordinate for center alignment
        return (x, 30)  # Default y-coordinate is 30 for the first line

    # Get the frame width
    frame_width = frame.shape[1]

    # First line of the text
    info_text1 = f"Taking Attendance!"
    x, y = get_center_position(info_text1, frame_width, cv2.FONT_HERSHEY_SIMPLEX, 0.7, 2)
    cv2.putText(frame, info_text1, (x, y), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 255, 0), 2)

    # Second line of the text
    info_text2 = f"Course: {course_id}, Section: {section_id}, Date: {current_date}"
    x, y = get_center_position(info_text2, frame_width, cv2.FONT_HERSHEY_SIMPLEX, 0.7, 2)
    cv2.putText(frame, info_text2, (x, y + 40), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 255, 0), 2)

    cv2.imshow("Taking Attendance", frame)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

conn.close()
video_capture.release()
cv2.destroyAllWindows()
