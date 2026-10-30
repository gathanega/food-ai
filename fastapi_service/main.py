"""Layanan AI food.ai: FastAPI + YOLOv8.

Jalankan:  uvicorn main:app --port 8001
Butuh:     pip install fastapi uvicorn python-multipart ultralytics pillow

Letakkan bobot hasil pelatihan di weights/best.pt. Nama kelas pada dataset
harus: babi, alkohol, sapi, ayam, sayur (sama dengan App\\Models\\Scan::KELAS).
"""
import io

from fastapi import FastAPI, File, HTTPException, UploadFile
from PIL import Image
from ultralytics import YOLO

app = FastAPI(title="food.ai inference")
model = YOLO("weights/best.pt")


@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    try:
        gambar = Image.open(io.BytesIO(await file.read())).convert("RGB")
    except Exception:
        raise HTTPException(status_code=400, detail="Berkas bukan gambar yang valid")

    hasil = model.predict(gambar, verbose=False)[0]
    if len(hasil.boxes) == 0:
        raise HTTPException(status_code=404, detail="Tidak ada objek dikenali")

    # Ambil deteksi dengan keyakinan tertinggi.
    i = int(hasil.boxes.conf.argmax())
    x1, y1, x2, y2 = hasil.boxes.xyxy[i].tolist()
    lebar, tinggi = gambar.size

    return {
        "kelas": hasil.names[int(hasil.boxes.cls[i])],
        "conf": float(hasil.boxes.conf[i]),
        "box": {  # persen terhadap ukuran gambar
            "x": round(x1 / lebar * 100, 2),
            "y": round(y1 / tinggi * 100, 2),
            "w": round((x2 - x1) / lebar * 100, 2),
            "h": round((y2 - y1) / tinggi * 100, 2),
        },
    }
