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