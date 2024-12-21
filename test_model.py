import cv2
import face_recognition
import pickle
import mysql.connector
from datetime import datetime

# Load model
with open('./model/face_model.pkl', 'rb') as model_file:
    model = pickle.load(model_file)

encodings = model['encodings']
labels = model['labels']

# Koneksi ke database MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",  # Ganti sesuai password MySQL Anda
    database="face_detection"
)
cursor = db.cursor()

# Inisialisasi webcam
cap = cv2.VideoCapture(0)
print("Webcam initialized")

while True:
    ret, frame = cap.read()
    if not ret:
        print("Failed to read frame")
        break

    # Konversi frame ke RGB
    rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    face_locations = face_recognition.face_locations(rgb_frame)
    face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

    for face_encoding, face_location in zip(face_encodings, face_locations):
        # Bandingkan wajah dengan model
        matches = face_recognition.compare_faces(encodings, face_encoding, tolerance=0.6)
        user_info = {"name": "Unknown", "nim": "","alamat": "", "email": "", "phone": ""}
        if True in matches:
            match_index = matches.index(True)
            user_info = labels[match_index]
            print(f"Recognized: {user_info['name']}")

            # Simpan ke database
            timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S') 
            sql = """
                INSERT INTO detection_history (name, nim, alamat, email, phone, timestamp)
                VALUES (%s, %s, %s, %s, %s, %s)
            """
            values = (user_info['name'], user_info['nim'], user_info['alamat'], user_info['email'], user_info['phone'], timestamp)
            cursor.execute(sql, values)
            db.commit()
            print("Data saved to database")

        # Gambar kotak di sekitar wajah
        top, right, bottom, left = face_location
        display_name = user_info['name']
        cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
        cv2.putText(frame, display_name, (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)

    # Tampilkan video
    cv2.imshow('Face Recognition', frame)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
cursor.close()
db.close()
print("Program ended")
