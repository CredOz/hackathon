package com.cog.arcaneDriver;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.ContentValues;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.provider.MediaStore;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.resource.drawable.GlideDrawable;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.GlideDrawableImageViewTarget;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;
import com.cog.arcaneDriver.adapter.RoundImageTransform;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.json.JSONArray;
import org.json.JSONObject;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.net.HttpURLConnection;
import java.net.URL;


@EActivity (R.layout.activity_image_upload)
public class ImageUpload extends AppCompatActivity {
    Uri mCapturedImageURI;
    String imgDecodableString;
    private static final int CAMERA_REQUEST = 1;
    String picturePath,profImage;

    String LiveUrl="http://demo.cogzidel.com/arcane_lite/driver/";

    @ViewById(R.id.txtArcane)
    ImageView profImg;

    @ViewById(R.id.btnTakePhoto)
    Button next;

    String strEmail,strFirstName,strLastName,strPassword,strConfirmPassword,strCity,strMobile,strCountyCode,strProfileImage;

    @AfterViews
    void signUpImage() {
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(), getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));

        Intent i = getIntent();
        strFirstName = i.getStringExtra("FirstName");
        strLastName = i.getStringExtra("LastName");
        strPassword = i.getStringExtra("Password");
        strCity = i.getStringExtra("City");
        strMobile = i.getStringExtra("Mobile");
        strCountyCode = i.getStringExtra("CountryCode");
        strEmail = i.getStringExtra("Email");





    }


    @Click({R.id.btnTakePhoto})
    void signinPage() {
        if (profImg.getDrawable() != null) {

            System.out.println("GOING TO DOCUMENT UPLOAD" + "FNAME==" + strFirstName + " " + "LNAME==>" + strLastName + " " + "MOBILE==" + strMobile + " " + "PASSWORD==" + strPassword + " " + "CITY==" + strCity + " " + "COUNTRY CODE==" + strCountyCode);

            //if(!profImage.isEmpty()&&!profImage.matches("")){
            Intent signin = new Intent(this, DocUpload_Activity_.class);
            signin.putExtra("profImage", profImage);
            signin.putExtra("FirstName", strFirstName);
            signin.putExtra("LastName", strLastName);
            signin.putExtra("Email", strEmail);
            signin.putExtra("Password", strPassword);
            signin.putExtra("Mobile", strMobile);
            signin.putExtra("City", strCity);
            signin.putExtra("CountryCode", strCountyCode);
            signin.putExtra("ProfilePicture", strProfileImage);
            startActivity(signin);

            System.out.println("IMAGE UPLOAD PAGE" + "FNAME==" + strFirstName + " " + "LNAME==>" + strLastName + " " + "MOBILE==" + strMobile + " " + "PASSWORD==" + strPassword + " " + "CITY==" + strCity + " " + "COUNTRY CODE==" + strCountyCode + "IMAGE PROFILEE==" + strProfileImage);
           /* }
          else{
                Toast.makeText(ImageUpload.this, R.string.attachPhoto, Toast.LENGTH_SHORT).show();
            }


        }*/
        }  else{
            android.support.v7.app.AlertDialog.Builder builder =
                    new android.support.v7.app.AlertDialog.Builder(ImageUpload.this, R.style.AppCompatAlertDialogStyle);
            builder.setMessage(getString(R.string.option));

            builder.setNegativeButton(getString(R.string.camera), new DialogInterface.OnClickListener() {


                @Override
                public void onClick(DialogInterface dialog, int which) {


                    ContentValues values = new ContentValues();
                    values.put(MediaStore.Images.Media.TITLE, "Image File name");
                    mCapturedImageURI = getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                    Intent cameraIntent = new Intent(
                            android.provider.MediaStore.ACTION_IMAGE_CAPTURE);
                    cameraIntent.putExtra("android.intent.extras.CAMERA_FACING", 1);

                    cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT,
                            mCapturedImageURI);
                    startActivityForResult(cameraIntent, CAMERA_REQUEST);


                }
            });
            builder.setNeutralButton(getString(R.string.gallery), new DialogInterface.OnClickListener() {

                @Override
                public void onClick(DialogInterface dialog, int which) {


                    Intent i = new Intent(Intent.ACTION_PICK,
                            android.provider.MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                    startActivityForResult(i, 100);


                }
            });


            builder.setPositiveButton(getString(R.string.close), new DialogInterface.OnClickListener() {

                @Override
                public void onClick(DialogInterface dialog, int which) {
                    // TODO Auto-generated method stub
                    dialog.cancel();
                }
            });


            builder.show();
        }
    }


    @Click({R.id.back})
    void back() {
       finish();
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(), getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));
    }

    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == CAMERA_REQUEST && resultCode == RESULT_OK) {

            String selectedImagePath = getRealPathFromURI(mCapturedImageURI);
            picturePath = selectedImagePath;
            System.out.println("CAMERA IMAGE"+picturePath);
            profImg.setScaleType(ImageView.ScaleType.FIT_XY);


            Glide.with(ImageUpload.this)
                    .load(picturePath)
                    .skipMemoryCache(true)
                    .transform(new RoundImageTransform(ImageUpload.this))
                    .into(new GlideDrawableImageViewTarget(profImg) {
                        @Override
                        public void onResourceReady(GlideDrawable drawable, GlideAnimation anim) {
                            super.onResourceReady(drawable, anim);
                        }
                    });


//            profImg.setImageBitmap(BitmapFactory.decodeFile(picturePath));

            if (Glide.isSetup()) {
                next.setText("Next");
            }
            else{
                next.setText(getResources().getString(R.string.take_ohoto));
            }
            new ImageuploadTask(this).execute();
        }

        System.out.println("Request Code+requestCode" + "Result Code" + resultCode + "data" + data);

        if (requestCode == 100 && resultCode == Activity.RESULT_OK
                && null != data) {

            Uri selectedImage = data.getData();
            String[] filePathColumn = {MediaStore.Images.Media.DATA};

            // Get the cursor
            Cursor cursor = getContentResolver().query(selectedImage,
                    filePathColumn, null, null, null);
            // Move to first row
            cursor.moveToFirst();

            int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
            picturePath = cursor.getString(columnIndex);
            cursor.close();

            // Set the Image in ImageView after decoding the String
            profImg.setScaleType(ImageView.ScaleType.FIT_XY);
            Glide.with(ImageUpload.this)
                    .load(picturePath)
                    .skipMemoryCache(true)
                    .transform(new RoundImageTransform(ImageUpload.this))
                    .into(new GlideDrawableImageViewTarget(profImg) {
                        @Override
                        public void onResourceReady(GlideDrawable drawable, GlideAnimation anim) {
                            super.onResourceReady(drawable, anim);
                        }
                    });

            if (Glide.isSetup()) {
                next.setText("Next");
            }
            else{
                next.setText(getResources().getString(R.string.take_ohoto));
            }
            new ImageuploadTask(this).execute();
        }
    }

    public String getRealPathFromURI(Uri contentUri)
    {
        try
        {
            String[] proj = {MediaStore.Images.Media.DATA};
            Cursor cursor = managedQuery(contentUri, proj, null, null, null);
            int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
            cursor.moveToFirst();
            return cursor.getString(column_index);
        }
        catch (Exception e)
        {
            return contentUri.getPath();
        }
    }


    private class ImageuploadTask extends AsyncTask<String, Void, Boolean>
    {
        private ProgressDialog dialog;
        private ImageUpload activity;

        public ImageuploadTask(ImageUpload activity)
        {
            this.activity = activity;
            context = activity;
            dialog = new ProgressDialog(context);
        }

        private Context context;

        protected void onPreExecute()
        {
            dialog = new ProgressDialog(context);
            dialog.setMessage("Uploading...");
            dialog.setIndeterminate(false);
            dialog.setCancelable(false);
            dialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            dialog.show();
        }

        @Override
        protected void onPostExecute(final Boolean success)
        {
            if (success)
            {
                if (dialog.isShowing())
                {
                    if(!activity.isFinishing())
                        dialog.dismiss();
                }
            }
            else
            {
                if (dialog.isShowing())
                {
                    if(!activity.isFinishing())
                        dialog.dismiss();
                }
            }
        }

        @Override
        protected Boolean doInBackground(final String... args)
        {
            try {
                // ... processing ...
                Upload_Server();
                return true;
            } catch (Exception e){
                Log.e("Schedule", "UpdateSchedule failed", e);
                return false;
            }
        }
    }


    protected void Upload_Server() {
        // TODO Auto-generated method stub
        System.out.println("After call progress");
        try{

            Log.e("Image Upload", "Inside Upload");

            HttpURLConnection connection = null;
            DataOutputStream outputStream = null;
            DataInputStream inputStream = null;

            String pathToOurFile = picturePath;
            //	  String pathToOurFile1 = imagepathcam;

            System.out.println("Before Image Upload"+picturePath);

            String urlServer= Constants.LIVEURL+"imageUpload/";
            System.out.println("URL SETVER"+urlServer);
            System.out.println("After Image Upload"+picturePath);
            String lineEnd = "\r\n";
            String twoHyphens = "--";
            String boundary =  "*****";

            int bytesRead, bytesAvailable, bufferSize;
            byte[] buffer;
            int maxBufferSize = 1*1024*1024;

            FileInputStream fileInputStream = new FileInputStream(new File(pathToOurFile));
            //  FileInputStream fileInputStream1 = new FileInputStream(new File(pathToOurFile1));

            URL url = new URL(urlServer);
            connection = (HttpURLConnection) url.openConnection();
            System.out.println("URL is "+url);
            System.out.println("connection is "+connection);
            // Allow Inputs & Outputs
            connection.setDoInput(true);
            connection.setDoOutput(true);
            connection.setUseCaches(false);

            // Enable POST method
            connection.setRequestMethod("POST");

            connection.setRequestProperty("Connection", "Keep-Alive");
            connection.setRequestProperty("Content-Type", "multipart/form-data;boundary="+boundary);

            outputStream = new DataOutputStream( connection.getOutputStream() );
            outputStream.writeBytes(twoHyphens + boundary + lineEnd);
            outputStream.writeBytes("Content-Disposition: form-data; name=\"uploadedfile\";filename=\"" + pathToOurFile +"\"" + lineEnd);
            outputStream.writeBytes(lineEnd);

            bytesAvailable = fileInputStream.available();
            bufferSize = Math.min(bytesAvailable, maxBufferSize);
            buffer = new byte[bufferSize];

            // Read file
            bytesRead = fileInputStream.read(buffer, 0, bufferSize);

            while (bytesRead > 0)
            {
                outputStream.write(buffer, 0, bufferSize);
                bytesAvailable = fileInputStream.available();
                bufferSize = Math.min(bytesAvailable, maxBufferSize);
                bytesRead = fileInputStream.read(buffer, 0, bufferSize);
            }

            outputStream.writeBytes(lineEnd);
            outputStream.writeBytes(twoHyphens + boundary + twoHyphens + lineEnd);

            // Responses from the server (code and message)
            int serverResponseCode = connection.getResponseCode();
            String serverResponseMessage = connection.getResponseMessage();


            System.out.println("image"+serverResponseMessage);

            fileInputStream.close();
            outputStream.flush();
            outputStream.close();

            DataInputStream inputStream1 = null;
            inputStream1 = new DataInputStream (connection.getInputStream());
            String str="";
            String Str1_imageurl="";

            while ((  str = inputStream1.readLine()) != null)
            {
                Log.e("Debug","Server Response "+str);

                Str1_imageurl = str;
                Log.e("Debug","Server Response String imageurl"+str);
            }
            inputStream1.close();
            System.out.println("image url"+Str1_imageurl);

            //get the image url and store
                profImage=Str1_imageurl.trim();
            JSONArray array = new JSONArray(profImage);
            JSONObject jsonObj  = array.getJSONObject(0);
            System.out.println("image name"+jsonObj.getString("image_name"));

            strProfileImage=jsonObj.optString("image_name");

                System.out.println("Profile Picture Path"+profImage);

        }

        catch(Exception e){

            e.printStackTrace();

        }
    }



}
