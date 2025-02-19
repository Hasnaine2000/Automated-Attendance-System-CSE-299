import cv2
import face_recognition
import os
import numpy as np

# Folder containing images
IMAGE_FOLDER = "photos"

# Dictionary to store encodings
known_face_encodings = []
known_face_names = []

# Load known faces dynamically from the folder
for image_name in os.listdir(IMAGE_FOLDER):
    image_path = os.path.join(IMAGE_FOLDER, image_name)
    image_bgr = cv2.imread(image_path)
    if image_bgr is not None:
        image_rgb = cv2.cvtColor(image_bgr, cv2.COLOR_BGR2RGB)
        encodings = face_recognition.face_encodings(image_rgb)
        if encodings:
            known_face_encodings.append(encodings[0])
            known_face_names.append(image_name.split('.')[0])  # Remove file extension




cv2.destroyAllWindows()






https://chatgpt.com/share/67b6080b-7920-8013-a93a-dda2561da0df
