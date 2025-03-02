import cv2
import face_recognition
import os
import mysql.connector
import numpy as np

# Database connection
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="attendancesystem"
)
cursor = conn.cursor()

# Folder containing images
IMAGE_FOLDER = "uploads"  # Now reading from 'uploads'

# Load known faces dynamically from the folder
for image_name in os.listdir(IMAGE_FOLDER):
    image_path = os.path.join(IMAGE_FOLDER, image_name)
    image_bgr = cv2.imread(image_path)
    
    if image_bgr is not None:
        image_rgb = cv2.cvtColor(image_bgr, cv2.COLOR_BGR2RGB)
        encodings = face_recognition.face_encodings(image_rgb)
        
        if encodings:
            face_encoding = encodings[0]
            
            # Extract ID and Name from filename (assuming format "ID-Name.jpg")
            base_name = os.path.splitext(image_name)[0]  # Remove file extension
            parts = base_name.split('-')

            if len(parts) >= 2:
                sid = parts[0].strip()  # ID part
                name = parts[1].strip()  # Name part
            else:
                print(f"Skipping {image_name} - Invalid filename format")
                continue

            face_blob = face_encoding.tobytes()  # Convert encoding to BLOB

            # Insert into database
            try:
                cursor.execute("INSERT INTO Student (sid, name, face) VALUES (%s, %s, %s)", (sid, name, face_blob))
                conn.commit()
                print(f"Stored {name} (ID: {sid}) in database.")
            except mysql.connector.IntegrityError:
                print(f"{name} (ID: {sid}) already exists in the database, skipping...")

# Close database connection
cursor.close()
conn.close()
print("Face encodings stored successfully.")
