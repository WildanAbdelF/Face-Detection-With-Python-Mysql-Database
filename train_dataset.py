import os
import cv2
import face_recognition
import pickle

# Path ke folder data
dataset_path = './data'
output_model_path = './model/face_model.pkl'

# Inisialisasi daftar encodings dan label
encodings = []
labels = []

# Iterasi melalui folder setiap user
for user_folder in os.listdir(dataset_path):
    user_path = os.path.join(dataset_path, user_folder)
    if not os.path.isdir(user_path):
        continue

    # Ambil NIM, nama, email, alamat, dan nomor HP dari nama folder
    user_info = user_folder.split('_')
    if len(user_info) != 5:
        print(f"[WARNING] Skipping invalid folder format: {user_folder}")
        continue

    user_data = {
        "nim": user_info[0],
        "name": user_info[1],
        "alamat": user_info[2],
        "email": user_info[3],
        "phone": user_info[4]
    }

    # Iterasi melalui setiap gambar
    for image_name in os.listdir(user_path):
        image_path = os.path.join(user_path, image_name)
        image = cv2.imread(image_path)
        rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

        # Deteksi wajah dan encode
        face_locations = face_recognition.face_locations(rgb_image)
        face_encodings = face_recognition.face_encodings(rgb_image, face_locations)

        for face_encoding in face_encodings:
            encodings.append(face_encoding)
            labels.append(user_data)

# Simpan model ke file
print("[INFO] Saving model to", output_model_path)
with open(output_model_path, 'wb') as model_file:
    pickle.dump({"encodings": encodings, "labels": labels}, model_file)
