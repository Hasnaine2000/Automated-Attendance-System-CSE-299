import cv2
import face_recognition
import os
import mysql.connector
import pickle  # Use pickle for safe encoding storage

# Database connection
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="attendancesystem"
)
cursor = conn.cursor()

# Folder containing images
IMAGE_FOLDER = "uploads"  # Folder where images are stored

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
                sid = parts[0].strip()  # Student ID
                name = parts[1].strip()  # Student Name
            else:
                print(f"Skipping {image_name} - Invalid filename format")
                continue

            face_blob = pickle.dumps(face_encoding)  # ✅ Convert encoding to BLOB using pickle

            # ✅ Check if the student already exists in the database
            cursor.execute("SELECT sid FROM student WHERE sid = %s", (sid,))
            result = cursor.fetchone()

            if result:
                # ✅ If student exists, update the face encoding
                cursor.execute("UPDATE student SET face = %s WHERE sid = %s", (face_blob, sid))
                print(f"Updated face encoding for {name} (ID: {sid}) in database.")
            else:
                # ✅ If student does not exist, insert new entry
                cursor.execute("INSERT INTO student (sid, name, face) VALUES (%s, %s, %s)", (sid, name, face_blob))
                print(f"Stored {name} (ID: {sid}) in database.")
            
            conn.commit()  # Save changes

# Close database connection
cursor.close()
conn.close()
print("Face encodings stored/updated successfully.")
