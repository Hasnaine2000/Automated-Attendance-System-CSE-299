import cv2 as cv 

def frame_resize(frame, scale = 0.75):
    width = int(frame.shape[1]*scale)
    height = int(frame.shape[0]*scale)
    dimentions = (width,height)
    return cv.resize(frame, dimentions, interpolation=cv.INTER_AREA)

capture = cv.VideoCapture('videos/vid1.mp4')

while True:
    isTrue, frame = capture.read()
    new_frame = frame_resize(frame)
    
    cv.imshow('video new', new_frame)


    if cv.waitKey(20) & 0xFF==ord('d'):
      break


capture.release()
cv.destroyAllWindows()
    







