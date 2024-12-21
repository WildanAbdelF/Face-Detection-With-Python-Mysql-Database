import sys
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

# Baca gambar dari argumen
image_path = sys.argv[1]
img = cv2.imread(image_path)
rgb_img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
face_locations = face_recognition.face_locations(rgb_img)
face_encodings = face_recognition.face_encodings(rgb_img, face_locations)

user_info = {"name": "Unknown", "nim": "", "alamat": "", "email": "", "phone": ""}
for face_encoding in face_encodings:
    matches = face_recognition.compare_faces(encodings, face_encoding)
    if True in matches:
        match_index = matches.index(True)
        user_info = labels[match_index]

        # Simpan ke database
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",  # Ganti sesuai password MySQL Anda
            database="face_detection"
        )
        cursor = db.cursor()
        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        sql = """
            INSERT INTO detection_history (name, nim, alamat, email, phone, timestamp)
            VALUES (%s, %s, %s, %s, %s, %s)
        """
        values = (user_info['name'], user_info['nim'], user_info['alamat'], user_info['email'], user_info['phone'], timestamp)
        cursor.execute(sql, values)
        db.commit()
        cursor.close()
        db.close()

print(user_info)
