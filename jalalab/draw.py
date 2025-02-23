import cv2 as cv 
import numpy as np

blank = np.zeros((500,500,3), dtype='uint8')
cv.imshow('Blank', blank)
"""
img = cv.imread('photos/cat1.jpg')
cv.imshow('Cat', img)
"""
blank[:] = 0,255,0
cv.imshow('Red', blank)


cv.waitKey(0)