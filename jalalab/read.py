import cv2 as cv 
"""
img = cv.imread('photos\cat2.jpg')
cv.imshow('Cat', img)
cv.waitKey(0)
"""
#reading videos 
'''
capture = cv.VideoCapture(r'videos\vid1.mp4')

while True:
    isTrue , frame = capture.read()
    cv.imshow('Video', frame)
    if cv.waitKey(20) & 0xFF==ord('d'):
     break

capture.release()
cv.destroyAllWindows()
'''
''''
img = cv.imread('photos\cat2.jpg')
cv.imshow('Cat', img)

def rescaleFrame(frame, scale=0.75):
    width = int(frame.shape[1] * scale)
    height = int(frame.shape[0] * scale)
    dimentions = (width,height)
    return cv.resize(frame, dimentions, interpolation=cv.INTER_AREA) 

cv.waitKey(0)

'''

capture = cv.VideoCapture(r'videos/vid1.mp4')

while True:
    isTrue, frame = capture.read() 
    cv.imshow('Video', frame)
    if cv.waitKey(20) & 0xFF==ord('d'):
      break

capture.release()
cv.destroyAllWindows()


















