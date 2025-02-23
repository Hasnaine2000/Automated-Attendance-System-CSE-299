"""
x = 5
y = "Hello, world!"

print(y)
print(x)
"""

"""""
x = 5 
y = 2 
print(x+y)
"""
"""""
global x 
x = "Kuttar bachha"

def myfunc():

   x = "bhalo manush"
   print("nihal ekta "+x)
    

myfunc()

print("nihal ekta "+x)
"""
#if any variable want to global that have to declare in function, otherwise it cant access 

""""
x = "awsome"

def myfunc():
    global x 
    x = "fantastic"

myfunc()
print("Pythone is "+x)
"""

""""
x = 5
y = "hello beauty"
z = 5.39
k = ["banana", "cherry", "asd"]
j = {"asd", "sss", "ppp"}
a = {"name": "pial", "age": 36}

print(type(x))
print(type(y))
print(type(z))
print(type(k))
print(type(j))
print(type(a))

x = 1 
y = 2.8 
z = 1j

a = float(x)
b = int (y)
c = complex(z)

print(a,b,c)
print(type(a),type(b),type(c))
"""
"""""
import random 
print(random.randrange(1,500))


x = int(1)
y = int(2.8)
z = int("3")

print(x,y,z)

a = float(1)
b = float(2.8)
c = float("3")
d = float("4.2")
print(a,b,c,d)

p = str("s1")
q = str(2)
r = str(3.0)
print(p,q,r)
"""
""""
a = "Hello, world!"
print(len(a))

txt = "tumi ki jano na , tumi ki bujho na, asdhaoshdo"

if "jano" in txt:
    print("Hae bhai ja chaisen ache")

print("henlo" not in txt)

b = "hello, world"
print(b[-5:-2])

a = "Hello, world!"
print(a.upper())
print(a.lower())
b =  " Hello ,  world!"
print(b.strip())
"""
#strip removes whitespace from the beginning and end
"""""
a = "pial is a goodboy"
print(a.replace("a", "o"))


a = " hello, world! "
print(a.upper())
print(a.lower())
print(a.strip())
print(a.replace("h","m"))
print(a.split(","))

#concate 
a = "Hello"
b = "world"
c = a+","+b
print(c)

#f-strings/format() method
age = 25

a = f"hello , my age is {age}"
print(a)

price = 10 
txt = f"price is {price: .2f} dollars"
print(txt)

#escape charectars 
txt = "we ar so called \"vikings\" from the north"
print(txt)

a = "Pial is a \"good\" boy "
print(a)


def myfunc():
    return False
if myfunc():
    print("its true")
else:
    print("its false")
    

#isinstance()//// will match the datatype with the var

x = 153.4
print(isinstance(x, float))


x = 4.5
y =  4.5
if x in y: 
    print("its maybe 4.5")
else: 
    print("its maybe 6")
"""
    
#list

list1 = ["apple", "banana", "cherry", "orange", "kiwi", "mango"]
list2 = [1,5,7,9,3,0]
list3 = [True,False,True]
list4 = ["abc", 34, True, 40, "male"]
list1[1:2] = ["blackcurrant", "watermelon"]
print(list1)