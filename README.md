# Automated Attendance System

## 📌 Overview
The **Automated Attendance System** is a project developed for the **CSE299** course. It leverages **face recognition technology** to streamline and automate the attendance-taking process in classrooms. This system ensures **accuracy, efficiency, and ease of management** for faculty and administrators.

## 🚀 Features
### 🔹 Admin Features
- 📝 **Add Students**: Upload a folder of student images for automatic registration. The system processes the images, extracts face encodings, and stores them in the database.
- 🏫 **Initialize Classrooms**: Set up classrooms by assigning courses, sections, faculty, and students.
- 🎛️ **Dashboard**: A centralized interface for managing the system efficiently.

### 🔹 Faculty Features
- 🎥 **Automated Attendance**: Use a webcam to take attendance using face recognition.
- ✅ **Manual Attendance**: Manually mark attendance if needed.
- 📅 **Attendance History**: View attendance records for specific courses, sections, and dates.

### 🔹 Core Functionalities
- 🤖 **Face Recognition**: Utilizes **machine learning algorithms** to detect and recognize student faces.
- 🗄️ **Database Integration**: Stores student information, face encodings, and attendance records in **MySQL**.
- 🌐 **Web Interface**: Built with **HTML, CSS, and TailwindCSS** for a responsive and intuitive user experience.

## 🛠️ Technologies Used
- **Frontend**: HTML, CSS, TailwindCSS
- **Backend**: PHP
- **Database**: MySQL
- **Face Recognition**: Python (`face_recognition`, `OpenCV`)

## 📌 How It Works
### 1️⃣ Student Registration
- Admin uploads a folder of student images.
- The system extracts face encodings and stores them in the database.

### 2️⃣ Classroom Initialization
- Admin assigns courses, sections, faculty, and students to classrooms.

### 3️⃣ Taking Attendance
- Faculty uses a webcam to capture student faces.
- The system matches detected faces with stored encodings and marks attendance in the database.

### 4️⃣ Manual Attendance
- Faculty manually marks attendance for students who were not detected.

### 5️⃣ Viewing Attendance History
- Faculty can view attendance records for specific courses, sections, and dates.

## 🗄️ Database Schema
The system uses the following database tables:
- **`admin`**: Stores admin credentials.
- **`faculty`**: Stores faculty credentials.
- **`student`**: Stores student details and face encodings.
- **`classroom`**: Maps courses, sections, faculty, and students.
- **`attendance`**: Stores attendance records.

## 👨‍💻 Team Members
- **Kazi Abdullah Al Hasnaine** - 2211688 642
- **Md. Asaduzzaman Sunny** - 2211702 642
- **Md Jalal Abedin Pial** - 2211158 642
- **Al Amin** - 2212251 042

## 🚀 How to Run
1. **Clone the repository** to your local machine inside htdocs folder.
2. **Set up the MySQL database** using the schema provided in `Documentation/database code - non relational.txt`.
3. **Configure the database connection** in `db_connect.php`.
4. **Start a local server** (e.g., XAMPP or WAMP) and place the project folder in the server's root directory.
5. **Access the system** via a web browser.

## 🔮 Future Improvements
- 📱 **Mobile Compatibility**: Add support for mobile devices.
- 📢 **Real-time Notifications**: Implement instant updates for attendance changes.
- 🎯 **Enhanced Accuracy**: Improve the face recognition algorithm for better detection.
- 📊 **Data Analytics**: Provide insights into student attendance trends.
- 🖥️ **Role-Based Access**: Implement different user roles for better system security.

## 📜 License
This project is developed for **educational purposes** and is not intended for commercial use.

---
**🎯 Developed as part of the CSE299 Course**

