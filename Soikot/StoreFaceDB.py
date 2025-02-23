import cv2
import face_recognition
import os
import mysql.connector
import numpy as np

# Database connection
conn = mysql.connector.connect(
    host="localhost",
    user="root",  # Change if necessary
    password="",  # Change if necessary
    database="attendancesystem"
)
cursor = conn.cursor()

# Folder containing images
IMAGE_FOLDER = "photos"

# Load known faces dynamically from the folder
for image_name in os.listdir(IMAGE_FOLDER):
    image_path = os.path.join(IMAGE_FOLDER, image_name)
    image_bgr = cv2.imread(image_path)
    if image_bgr is not None:
        image_rgb = cv2.cvtColor(image_bgr, cv2.COLOR_BGR2RGB)
        encodings = face_recognition.face_encodings(image_rgb)
        if encodings:
            face_encoding = encodings[0]
            name = image_name.split('.')[0]  # Extract name without file extension
            sid = name.replace(" ", "_")  # Generate student ID from name
            face_blob = face_encoding.tobytes()  # Convert encoding to BLOB

            # Insert into database
            try:
                cursor.execute("INSERT INTO Student (sid, name, face) VALUES (%s, %s, %s)", (sid, name, face_blob))
                conn.commit()
                print(f"Stored {name}'s face encoding in database.")
            except mysql.connector.IntegrityError:
                print(f"{name} already exists in the database, skipping...")

# Close database connection
cursor.close()
conn.close()
print("Face encodings stored successfully.")
