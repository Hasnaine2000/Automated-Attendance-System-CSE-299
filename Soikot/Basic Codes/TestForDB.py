import cv2
import face_recognition
import numpy as np
import mysql.connector
import pickle

# MySQL Database Configuration
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "attendancesystem"
}

# Connect to MySQL database
conn = mysql.connector.connect(**DB_CONFIG)
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

# Start video capture
video_capture = cv2.VideoCapture(0)
frame_skip = 10  # Process face recognition every 10th frame
frame_count = 0

while True:
    ret, frame = video_capture.read()
    if not ret:
        break

    frame_count += 1
    if frame_count % frame_skip == 0:
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_frame)
        face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

        for face_encoding, face_location in zip(face_encodings, face_locations):
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            name = "Unknown"

            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            best_match_index = np.argmin(face_distances) if face_distances.size > 0 else None

            if best_match_index is not None and matches[best_match_index]:
                name = known_face_names[best_match_index]
                print(f"{name} is present")  # Print when a known face is recognized

            # Draw a rectangle and label on the detected face
            top, right, bottom, left = face_location
            cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
            cv2.putText(frame, name, (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

    cv2.imshow("Video", frame)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

video_capture.release()
cv2.destroyAllWindows()
