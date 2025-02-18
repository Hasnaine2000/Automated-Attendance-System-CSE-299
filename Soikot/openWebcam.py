import cv2
import numpy as np

cap = cv2.VideoCapture(0)

while True:
    ret,frame = cap.read()  #frame = numpy array that is going to represesnt the image and ret = is it working properly
    cv2.imshow('frame', frame)
    if cv2.waitKey(1) == ord('q'):  #button to close the webcam
        break 
cap.release()
cv2.destroyAllWindows()

