import cv2 as cv
import face_recognition as fr 

img = cv.imread('photos/pic1.jpg')
rgb_img = cv.cvtColor(img, cv.COLOR_BGR2RGB)
img_encoding = fr.face_encodings(rgb_img)[0]

img2 = cv.imread('photos/pic2.jpg')
rgb_img2 = cv.cvtColor(img2, cv.COLOR_BGR2RGB)
img_encoding2 = fr.face_encodings(rgb_img2)[0]

result = fr.compare_faces([img_encoding],img_encoding2) 
print("Result : ", result)

cv.imshow("img",img)
cv.imshow("img 2", img2)
cv.waitKey(0)