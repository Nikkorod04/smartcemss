# Google Cloud Vision API Setup Guide

This guide will help you set up Google Cloud Vision API for OCR capabilities in SmartCEMES.

## Prerequisites

- Google Cloud Project (create at https://console.cloud.google.com)
- Service Account with Vision API enabled
- JSON credentials file from the service account

## Step-by-Step Setup

### 1. Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project or select an existing one
3. Note the Project ID

### 2. Enable Vision API

1. In the Google Cloud Console, go to **APIs & Services > Library**
2. Search for "Cloud Vision API"
3. Click on "Cloud Vision API"
4. Click **ENABLE**

### 3. Create a Service Account

1. Go to **APIs & Services > Credentials**
2. Click **Create Credentials** > **Service Account**
3. Fill in the service account details:
   - Service account name: `smartcemes-ocr`
   - Service account ID: (auto-generated)
   - Description: "OCR service for assessment form processing"
4. Click **CREATE AND CONTINUE**
5. Grant roles (skip if not required for your setup)
6. Click **CONTINUE** then **DONE**

### 4. Create and Download JSON Key

1. In **Credentials** page, find your service account under "Service Accounts"
2. Click on the service account email
3. Go to the **KEYS** tab
4. Click **ADD KEY** > **Create new key**
5. Choose **JSON** format
6. Click **CREATE**
7. The JSON file will download automatically

### 5. Place Credentials in Laravel

You have two options:

### Option A: Default Filename (Easiest)
Rename your JSON file to `google-credentials.json` and place it at:
```
storage/app/google-credentials.json
```

### Option B: Keep Your Current Filename (Recommended)
Place your JSON file anywhere in `storage/app/` and add this to your `.env`:
```env
GOOGLE_CREDENTIALS_FILE=smartcemes-51502d0f238e.json
```

Then place the file at:
```
storage/app/smartcemes-51502d0f238e.json
```

**We recommend Option B** - you keep your original filename and the code adapts to it!

### 6. Verify Setup

Run this command to test the connection:

```bash
php artisan tinker
$ocr = new App\Services\OcrService();
# Should not throw any errors
```

## Usage

### Import from Image

1. Go to Create Assessment page
2. Select "Upload File" input method
3. Upload a JPG or PNG image of the form
4. The system will automatically:
   - Use Google Vision API to extract text
   - Parse the extracted text
   - Map fields to form inputs
   - Show preview for review
5. Click "Confirm & Populate Fields"

### Import from PDF

1. Go to Create Assessment page
2. Select "Upload File" input method
3. Upload a PDF file
4. For scanned PDFs:
   - System tries OCR first via Google Vision
   - Falls back to text extraction if OCR fails
5. For text-based PDFs:
   - Text is extracted directly
6. Review and confirm

## Features

- ✅ **Image OCR**: JPG, PNG images of forms
- ✅ **PDF OCR**: Scanned PDFs and image-based PDFs
- ✅ **Text Extraction**: Text-based PDFs
- ✅ **Confidence Scoring**: Shows OCR confidence level
- ✅ **Field Mapping**: Automatically maps extracted data to form fields
- ✅ **Fallback Methods**: Uses alternative parsing if OCR fails

## Environment Variables (Optional)

Add these to your `.env` file if you want to customize behavior:

```env
# Specify your Google credentials JSON filename (default: google-credentials.json)
GOOGLE_CREDENTIALS_FILE=smartcemes-51502d0f238e.json

# You can also add these if needed:
GOOGLE_VISION_ENABLED=true
GOOGLE_CLOUD_PROJECT=your-project-id
```

**Note:** Only `GOOGLE_CREDENTIALS_FILE` is required if your JSON file has a different name.

## Cost Considerations

Google Cloud Vision API pricing:
- First 1,000 requests per month: FREE
- After that: ~$1.50 per 1,000 requests

For typical usage with ~100 assessments/month = $0.15/month

## Troubleshooting

### "Credentials file not found"
- Verify file is at: `storage/app/your-filename.json`
- If using a custom filename, make sure it's set in `.env`:
  ```env
  GOOGLE_CREDENTIALS_FILE=smartcemes-51502d0f238e.json
  ```
- Check file permissions (should be readable)

### "No text detected in image"
- Image may be too blurry or low quality
- Try a clearer photo or scan
- Ensure form fields are clearly visible

### "OCR service not configured"
- Credentials file is missing or path is incorrect
- But system will still allow manual entry or CSV/Excel import

### API Authentication Errors
- Verify service account has Vision API access
- Check that the JSON key is valid and not expired
- Re-download a new key from Google Cloud Console
- Make sure you're using the correct filename in `.env`

## File Structure

```
storage/
├── app/
│   └── google-credentials.json  ← Place credentials here
└── ...

app/
├── Services/
│   ├── OcrService.php           ← OCR implementation
│   ├── AssessmentDocumentParser.php  ← Updated to use OCR
│   └── AssessmentFieldMapper.php
└── ...
```

## Support

For more information:
- [Google Cloud Vision API Docs](https://cloud.google.com/vision/docs)
- [Google Cloud Vision PHP Client](https://github.com/googleapis/google-cloud-php-vision)
