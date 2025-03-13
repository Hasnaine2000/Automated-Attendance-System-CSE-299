import cv2
import face_recognition
import numpy as np
import mysql.connector
import pickle
import time

# MySQL Database Configuration
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "attendancesystem"
}

# Function to create a new database connection
def create_connection():
    return mysql.connector.connect(**DB_CONFIG)

# Create an initial connection
conn = create_connection()
cursor = conn.cursor()

# Dictionary to store encodings
known_face_encodings = []
known_face_names = []

# Retrieve encoded faces from database
cursor.execute("SELECT sid, name, face FROM student")
for sid, name, face_blob in cursor.fetchall():
    if face_blob:  # Ensure face data exists
        face_encoding = pickle.loads(face_blob)  # Convert BLOB to NumPy array
        known_face_encodings.append(face_encoding)
        known_face_names.append(name)  # Store student names

# Close the database connection
cursor.close()
conn.close()

# Initialize counters for each recognized student
student_detection_counts = {name: 0 for name in known_face_names}
students_saved = set()  # To track students who have already been stored in studentTest

# Start video capture
video_capture = cv2.VideoCapture(0)
frame_skip = 10  # Process face recognition every 10th frame
frame_count = 0
last_check_time = time.time()  # To track 2-second intervals

# 8 detections threshold for recognizing the student
detection_threshold = 8  # 8 detections

# Start checking
start_time = time.time()  # Track start time for 20-second duration
while True:
    ret, frame = video_capture.read()
    if not ret:
        break

    # Check if 20 seconds have passed
    elapsed_time = time.time() - start_time
    if elapsed_time > 20:
        print("20 seconds have passed. Closing the program.")
        break

    # Process face recognition every 2 seconds
    if time.time() - last_check_time >= 2:
        last_check_time = time.time()  # Reset the last check time for the next interval
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_frame)
        face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

        recognized_this_frame = set()  # Track recognized faces in the current frame

        for face_encoding, face_location in zip(face_encodings, face_locations):
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            name = "Unknown"

            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances) if face_distances.size > 0 else None

            if best_match_index is not None and matches[best_match_index]:
                name = known_face_names[best_match_index]
                recognized_this_frame.add(name)
                print(f"{name} is present")  # Print when a known face is recognized

                # Increment the detection count for this student
                student_detection_counts[name] += 1

                # If a student has been recognized 8 times, add them to the studentTest table (only once)
                if student_detection_counts[name] >= detection_threshold and name not in students_saved:
                    # Re-establish database connection
                    conn = create_connection()
                    cursor = conn.cursor()
                    try:
                        # Query to get the correct sid for the student based on their name
                        cursor.execute("SELECT sid FROM student WHERE name = %s", (name,))
                        result = cursor.fetchone()

                        if result:
                            sid = result[0]  # Get the sid from the query result
                            # Store the student in the studentTest table
                            cursor.execute("INSERT INTO studentTest (sid, name) VALUES (%s, %s)", 
                                           (sid, name))
                            conn.commit()
                            print(f"{name} (SID: {sid}) added to studentTest")
                            students_saved.add(name)  # Mark the student as saved
                        else:
                            print(f"Student {name} not found in the database")

                    except mysql.connector.Error as e:
                        print(f"Error inserting into database: {e}")
                    finally:
                        cursor.close()
                        conn.close()  # Close the connection after use

            # Draw a rectangle and label on the detected face
            top, right, bottom, left = face_location
            cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
            cv2.putText(frame, name, (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

    # Display the frame
    cv2.imshow("Video", frame)

    # Break if the user presses 'q' or after 20 seconds
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Close the final database connection and clean up
conn.close()
video_capture.release()
cv2.destroyAllWindows()
