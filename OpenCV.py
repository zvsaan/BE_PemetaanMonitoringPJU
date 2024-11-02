import cv2
import mediapipe as mp
import numpy as np
from sklearn.neighbors import KNeighborsClassifier

# Inisialisasi Mediapipe dan model sederhana
mp_hands = mp.solutions.hands
hands = mp_hands.Hands(min_detection_confidence=0.7, min_tracking_confidence=0.5)
mp_draw = mp.solutions.drawing_utils

hand_shapes = {
    'A': np.array([0, 1, 1, 1, 1, 1, 0, 1, 0, 0, 1, 1, 1, 0, 1, 0, 0, 0, 0, 1]),
    'B': np.array([1, 1, 0, 1, 0, 1, 1, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 0, 0]),
    'C': np.array([0, 1, 0, 1, 0, 0, 1, 0, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 0, 1]),
}

# Latih model KNN sederhana
knn = KNeighborsClassifier(n_neighbors=3)
X = list(hand_shapes.values())
y = list(hand_shapes.keys())
knn.fit(X, y)

cap = cv2.VideoCapture(0)

while cap.isOpened():
    success, img = cap.read()
    img = cv2.flip(img, 1)
    if not success:
        break

    img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    results = hands.process(img_rgb)

    if results.multi_hand_landmarks:
        for hand_landmarks in results.multi_hand_landmarks:
            # Mendapatkan posisi setiap jari
            landmarks = []
            for lm in hand_landmarks.landmark:
                landmarks.append((lm.x, lm.y))

            # Menentukan bentuk tangan dalam format sederhana
            hand_shape = []
            for i, (x, y) in enumerate(landmarks):
                if i < 20:  # Ambil 20 landmark pertama sebagai contoh
                    hand_shape.append(1 if y < 0.5 else 0)

            hand_shape = np.array(hand_shape).reshape(1, -1)

            # Prediksi huruf
            if hand_shape.shape[1] == 20:
                prediction = knn.predict(hand_shape)[0]
                cv2.putText(img, f'Huruf: {prediction}', (10, 50), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 0, 0), 2)

            # Gambar landmark
            mp_draw.draw_landmarks(img, hand_landmarks, mp_hands.HAND_CONNECTIONS)

    cv2.imshow("Sign Language Detection", img)
    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
