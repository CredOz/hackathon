package com.cog.arcaneDriver;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.ContentValues;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.provider.MediaStore;
import android.support.v7.app.AppCompatActivity;
import android.text.InputFilter;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NetworkError;
import com.android.volley.NoConnectionError;
import com.android.volley.ParseError;
import com.android.volley.Response;
import com.android.volley.ServerError;
import com.android.volley.TimeoutError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.cog.arcaneDriver.adapter.AppController;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;
import com.cog.arcaneDriver.adapter.RoundImageTransform;
import com.google.i18n.phonenumbers.NumberParseException;
import com.google.i18n.phonenumbers.PhoneNumberUtil;
import com.google.i18n.phonenumbers.Phonenumber;
import com.mobsandgeeks.saripaar.ValidationError;
import com.mobsandgeeks.saripaar.Validator;
import com.mobsandgeeks.saripaar.annotation.Length;
import com.mobsandgeeks.saripaar.annotation.NotEmpty;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.List;

@EActivity(R.layout.activity_edit_profile)
public class EditProfileActivity extends AppCompatActivity implements CountryCodePicker.OnCountryChangeListener, Validator.ValidationListener {

    Validator validator;
    public String userID, firstName, lastName, email, mobileNumber, countryCode, profileImage, profileImageNew="null", status, message,driverUpdateURL;
    private static final int CAMERA_CAPTURE_IMAGE = 100;
    public static final int MEDIA_TYPE_IMAGE = 1;
    String picturePath,profImage,strSelectedCategory,strCarCategory,strCategory;
    ProgressDialog progressDialog;

    SharedPreferences.Editor editor;

    String[] carcategory;
    JSONObject strJsonCategory;

    Spinner spinCarCategory;

    private Uri fileUri; // file url to store image/video

    @ViewById(R.id.profileImage)
    ImageView edtProfileImage;

    @ViewById(R.id.backButton)
    ImageButton backButton;


    @ViewById(R.id.save_button)
    Button saveButton;

    @NotEmpty(message = "")
    @Length (min=3, message = "Minimum of 3 characters")
    @ViewById(R.id.edtFirstName)
    EditText inputFirstName;

    @NotEmpty(message = "")
    @Length (min=3, message = "Minimum of 3 characters")
    @ViewById(R.id.edtLastName)
    EditText inputLastName;

    @NotEmpty
    @ViewById(R.id.edtCountryCode)
    TextView inputCountryCode;

    @NotEmpty
    @ViewById(R.id.edtMobile)
    EditText inputMobileNumber;

    @ViewById(R.id.edtEmail)
    EditText inputEmail;

    @ViewById(R.id.ccp)
    CountryCodePicker ccp;
    ArrayAdapter<String> adapteradapter;

    @AfterViews
    void settingsActivity() {
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(), getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));

        SharedPreferences prefs = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
        userID = prefs.getString("driverid", null);

        spinCarCategory=(Spinner)findViewById(R.id.car_category);
        //userID="5857f2cdda71b462688b4567";
        System.out.println("UserID in settings" + userID);
        validator = new Validator(this);
        validator.setValidationListener(this);
        ccp.setOnCountryChangeListener(this);
        //Change Font to Whole View

  /*      Intent i=getIntent();
        firstName=i.getStringExtra("firstName");
        lastName=i.getStringExtra("lastName");
        email=i.getStringExtra("email");
        mobileNumber=i.getStringExtra("mobileNumber");
        countryCode=i.getStringExtra("coutrycode");
        strCategory=i.getStringExtra("carcategory");
        profileImage=i.getStringExtra("profileimage");

        //displayDetails();

        if(firstName!=null)
        {
            inputFirstName.setText(firstName);
        }

        if(lastName!=null)
        {
            inputLastName.setText(lastName);
        }

        if(email!=null)
        {
            inputEmail.setText(email);
        }

        if(mobileNumber!=null)
        {
            inputMobileNumber.setText(mobileNumber);
        }

        if(countryCode!=null)
        {
            inputCountryCode.setText(countryCode);
        }

        if(profileImage!=null)
        {
            Glide.with(EditProfileActivity.this)
                    .load(profileImage)
                    .diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .transform(new RoundImageTransform(EditProfileActivity.this))
                    .into(edtProfileImage);
        }*/
        getCarCategory();
        displayDetails();



        spinCarCategory.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

                strSelectedCategory = parent.getItemAtPosition(position).toString();

            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });


    }

    private void getCarCategory()
    {

        final String url="http://demo.cogzidel.com/arcanemobile/drivers/getcarcategory";
        System.out.println("URL is"+url);
        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        carcategory=new String[response.length()+1];
                        for (int i = 0; i < response.length(); i++) {
                            try
                            {
                                strJsonCategory = response.getJSONObject(i);
                                strCarCategory= strJsonCategory.getString("car");
                                Log.d("OUTPUT IS",strCarCategory);
                                carcategory[0]="Select car category";
                                carcategory[i+1]=strCarCategory;
                                System.out.println("CATEGORY"+carcategory[i]);
                                adapteradapter  = new ArrayAdapter<String>(EditProfileActivity.this, R.layout.spinner_item, carcategory);
                                spinCarCategory.setAdapter(adapteradapter);

                                if (strCategory!=null) {

                                    if(!strCategory.equals("null")&&!strCategory.equals("")){

                                        int spinnerPosition = adapteradapter.getPosition(strCategory);
                                        spinCarCategory.setSelection(spinnerPosition);

                                    }

                                }
                            } catch (JSONException e) {

                                e.printStackTrace();
                            }
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error instanceof TimeoutError || error instanceof NoConnectionError) {

                    Toast.makeText(getApplicationContext(),"No Intenet Connection", Toast.LENGTH_SHORT).show();
                } else if (error instanceof AuthFailureError) {
                } else if (error instanceof ServerError) {
                } else if (error instanceof NetworkError) {

                    Toast.makeText(getApplicationContext(),"No Net", Toast.LENGTH_SHORT).show();
                } else if (error instanceof ParseError) {
                }
            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
    }

    public void displayDetails() {
        showDialog();
        final String url = Constants.LIVEURL + "editProfile/user_id/"+userID;
        System.out.println("RiderProfileURL==>"+url);
        final JsonArrayRequest signUpReq = new JsonArrayRequest(url, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                for (int i=0;i<response.length();i++){
                    try {
                        JSONObject jsonObject = response.getJSONObject(i);
                        status = jsonObject.optString("status");
                        message = jsonObject.optString("message");

                        if(status.equals("Success"))
                        {
                            firstName=jsonObject.optString("firstname");
                            lastName=jsonObject.optString("lastname");
                            email=jsonObject.optString("email");
                            mobileNumber=jsonObject.optString("mobile");
                            profileImage=jsonObject.optString("profile_pic");
                            countryCode=jsonObject.optString("country_code");

//                            savepreferences();


                            try
                            {
                                inputFirstName.setText(firstName.replaceAll("%20"," "));
                                inputLastName.setText(lastName.replaceAll("%20"," "));
                                inputEmail.setText(email);
                                inputMobileNumber.setText(mobileNumber);
                                inputCountryCode.setText(countryCode);

                                Glide.with(EditProfileActivity.this)
                                        .load(profileImage)
                                        .diskCacheStrategy(DiskCacheStrategy.NONE)
                                        .skipMemoryCache(true)
                                        .transform(new RoundImageTransform(EditProfileActivity.this))
                                        .into(edtProfileImage);
                            } catch (NullPointerException e){
                                e.printStackTrace();
                            }
                            dismissDialog();
                        } else {
//                            Toast.makeText(getApplicationContext(), message,Toast.LENGTH_SHORT).show();
                            dismissDialog();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    } catch (NullPointerException e) {
                        e.printStackTrace();
                    }
                    dismissDialog();
                }
            }
        },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        if (volleyError instanceof NoConnectionError){
                            dismissDialog();
                            Toast.makeText(getApplicationContext(), "No internet connection", Toast.LENGTH_SHORT).show();
                        }
                    }
                });

        AppController.getInstance().addToRequestQueue(signUpReq);
        signUpReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 0, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
    }


    @Click(R.id.edtCountryCode)
    public void countryCode(View view) {
        CountryCodeDialog.openCountryCodeDialog(ccp);//Open country code dialog
    }

    @Click(R.id.profileImage)
    public void updateProfileImage() {
        android.support.v7.app.AlertDialog.Builder builder =
                new android.support.v7.app.AlertDialog.Builder(EditProfileActivity.this, R.style.AppCompatAlertDialogStyle);
        builder.setMessage(getString(R.string.option));

        builder.setNegativeButton(getString(R.string.camera), new DialogInterface.OnClickListener() {


            @Override
            public void onClick(DialogInterface dialog, int which) {


                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.TITLE, "Image File name");
                fileUri = getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                Intent cameraIntent = new Intent(
                        android.provider.MediaStore.ACTION_IMAGE_CAPTURE);
                cameraIntent.putExtra("android.intent.extras.CAMERA_FACING", 1);

                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT,
                        fileUri);
                startActivityForResult(cameraIntent, CAMERA_CAPTURE_IMAGE);


            }
        });
        builder.setNeutralButton(getString(R.string.gallery), new DialogInterface.OnClickListener()
        {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                Intent i = new Intent(Intent.ACTION_PICK,
                        android.provider.MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                startActivityForResult(i, MEDIA_TYPE_IMAGE);


            }
        });


        builder.setPositiveButton(getString(R.string.close), new DialogInterface.OnClickListener()
        {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                // TODO Auto-generated method stub
                dialog.cancel();
            }
        });


        builder.show();

    }


    @Click(R.id.save_button)
    void saveProfile()
    {
        validator.validate();
    }

    @Click(R.id.backButton)
    void goBack(){
        Intent i=new Intent(EditProfileActivity.this,SettingActivity_.class);
        startActivity(i);
        finish();
    }

    private void updateProfile() {

        firstName=inputFirstName.getText().toString().trim();
        lastName=inputLastName.getText().toString().trim();
        countryCode=inputCountryCode.getText().toString().trim();
        mobileNumber=inputMobileNumber.getText().toString().trim();
        email=inputEmail.getText().toString().trim();
        firstName=firstName.replaceAll(" ","%20");
        lastName=lastName.replaceAll(" ","%20");
        showDialog();
        if(profileImageNew.equals(null)||profileImageNew.equals("null"))
        {
           driverUpdateURL = Constants.LIVEURL + "updateDetails/user_id/"+userID+"/firstname/"+firstName+"/lastname/"+lastName+"/mobile/"+mobileNumber+"/country_code/"+countryCode+"/city/"+"madurai"+"/email/"+email;
        } else {
            driverUpdateURL = Constants.LIVEURL + "updateDetails/user_id/"+userID+"/firstname/"+firstName+"/lastname/"+lastName+"/mobile/"+mobileNumber+"/country_code/"+countryCode+"/profile_pic/"+profileImageNew+"/city/"+"madurai"+"/email/"+email;
        }
        System.out.println("RiderUpdateProfileURL==>"+driverUpdateURL);
        final JsonArrayRequest signUpReq = new JsonArrayRequest(driverUpdateURL, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                for (int i=0;i<response.length();i++){
                    try {
                        JSONObject jsonObject = response.getJSONObject(i);
                        status = jsonObject.optString("status");
                        message = jsonObject.optString("message");

                        if(status.equals("Success")){
//                            savepreferences();
                            Intent intent = new Intent(EditProfileActivity.this,SettingActivity_.class);
                            startActivity(intent);
                            finish();
                        } else {
//                            Toast.makeText(getApplicationContext(), message,Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    } catch (NullPointerException e) {
                        e.printStackTrace();
                    }
                    dismissDialog();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError volleyError) {
                if (volleyError instanceof NoConnectionError){
                    dismissDialog();
                    Toast.makeText(getApplicationContext(), "No internet Connection",Toast.LENGTH_SHORT).show();
                }
            }
        });

        AppController.getInstance().addToRequestQueue(signUpReq);
        signUpReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 0, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
    }

    @Override
    public void onCountrySelected() {
        inputCountryCode.setText(ccp.getSelectedCountryCodeWithPlus());
    }

    private boolean validateCountryCode() {

        if (inputCountryCode.getText().toString().trim().isEmpty()) {
            inputCountryCode.setError("");
            inputCountryCode.setError(getString(R.string.enter_valid_cc));
            return false;
        } else if (inputCountryCode.getText().toString().equals("CC")) {
            inputCountryCode.setError("");
            inputCountryCode.setError(getString(R.string.enter_valid_cc));
            return false;
        } else {
            inputCountryCode.setError(null);
        }
        //equestFocus(countrycode);

        return true;
    }

    private boolean validatePhone() {
        if(inputMobileNumber.getText().toString().trim().isEmpty()) {
            inputMobileNumber.setError(getString(R.string.enter_mobile_number));
            return false;
        }
        else if (inputCountryCode.getText().toString().trim().isEmpty())
        {
            inputMobileNumber.setError(getString(R.string.enter_valid_cc));
            return false;
        }
        else  if (!inputMobileNumber.getText().toString().trim().isEmpty())
        {
            if (inputMobileNumber.getText().toString().substring(0, 1).matches("0")) {
                inputMobileNumber.setError("Enter a valid number");
                return false;
            } else {
                int maxLengthofEditText = 15;
                inputMobileNumber.setFilters(new InputFilter[]{new InputFilter.LengthFilter(maxLengthofEditText)});
                inputMobileNumber.setError(null);
            }
            return true;
        }

        return true;
    }

    private boolean validateUsing_libphonenumber()
    {
        countryCode = inputCountryCode.getText().toString();
        mobileNumber = inputMobileNumber.getText().toString();
        if (validatePhone() && validateCountryCode()) {
            System.out.println("CountryCode==>" + countryCode);
            if (Build.VERSION.SDK_INT < Build.VERSION_CODES.LOLLIPOP) {
                countryCode = countryCode.replace("+", "");
            }
            System.out.println("SDK_VERSION==>" + Build.VERSION.SDK_INT);
            System.out.println("SDK_VERSION_RELEASE" + Build.VERSION.RELEASE);
            System.out.println("CountryCode1==>" + countryCode);
            PhoneNumberUtil phoneNumberUtil = PhoneNumberUtil.getInstance();
            String isoCode = phoneNumberUtil.getRegionCodeForCountryCode(Integer.parseInt(countryCode));
            Phonenumber.PhoneNumber phoneNumber = null;

            try {
                //phoneNumber = phoneNumberUtil.parse(phNumber, "IN");  //if you want to pass region code
                phoneNumber = phoneNumberUtil.parse(mobileNumber, isoCode);
            } catch (NumberParseException e) {
                System.err.println(e);
            }

            boolean isValid = phoneNumberUtil.isValidNumber(phoneNumber);
            if (isValid) {
                String internationalFormat = phoneNumberUtil.format(phoneNumber, PhoneNumberUtil.PhoneNumberFormat.INTERNATIONAL);
                return true;
            } else {
                inputMobileNumber.setError(getString(R.string.enter_a_valid_mobile_number));
                return false;
            }
        }
        return true;
    }

    @Override
    public void onValidationSucceeded() {
        if (!validateCountryCode()) {

        }
        else if (!validatePhone()) {

        } else if (!validateUsing_libphonenumber()) {
            inputMobileNumber.setError(getString(R.string.invalid_mobile_number));
        } else {
            updateProfile();
        }

    }


    @Override
    public void onValidationFailed(List<ValidationError> errors) {
        for (ValidationError error : errors) {
            View view = error.getView();
            String message = error.getCollatedErrorMessage(this);

            // Display error messages ;)
            if (view instanceof EditText) {
                ((EditText) view).setError(message);
            } else {
                Toast.makeText(this, message, Toast.LENGTH_LONG).show();
            }
        }
    }

    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == CAMERA_CAPTURE_IMAGE && resultCode == RESULT_OK) {

            String selectedImagePath = getRealPathFromURI(fileUri);
            picturePath = selectedImagePath;

            edtProfileImage.setScaleType(ImageView.ScaleType.FIT_XY);
//                edtProfileImage.setImageBitmap(BitmapFactory.decodeFile(picturePath));
            Glide.with(EditProfileActivity.this)
                    .load(picturePath)
                    .diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .transform(new RoundImageTransform(EditProfileActivity.this))
                    .into(edtProfileImage);

            new ImageuploadTask(this).execute();

        } /*else if (requestCode == RESULT_LOAD_IMAGE && resultCode == RESULT_OK && null != data) {*/

        else if (requestCode == MEDIA_TYPE_IMAGE && resultCode == RESULT_OK && null != data) {

//            String single_path = data.getStringExtra("single_path");
            Uri selectedImage = data.getData();
            String[] filePathColumn = { MediaStore.Images.Media.DATA };

            Cursor cursor = getContentResolver().query(selectedImage,
                    filePathColumn, null, null, null);
            // Move to first row
            cursor.moveToFirst();

            int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
            picturePath = cursor.getString(columnIndex);
            cursor.close();
//            edtProfileImage.setImageBitmap(BitmapFactory.decodeFile(picturePath));
            Glide.with(EditProfileActivity.this)
                    .load(picturePath)
                    .diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .transform(new RoundImageTransform(EditProfileActivity.this))
                    .into(edtProfileImage);
            new ImageuploadTask(EditProfileActivity.this).execute();
        }
    }

    public String getRealPathFromURI(Uri contentUri)
    {
        try
        {
            String[] proj = {MediaStore.Images.Media.DATA};
            Cursor cursor =  this.getContentResolver().query(contentUri, proj, null, null, null);
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
        private EditProfileActivity activity;

        public ImageuploadTask(EditProfileActivity activity)
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

            String urlServer=Constants.LIVEURL+"imageUpload/";
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

            profileImageNew=jsonObj.optString("image_name");

            System.out.println("Profile Picture Path"+profImage);

        }

        catch(Exception e){

            e.printStackTrace();

        }
    }
    public void showDialog()
    {
        progressDialog = new ProgressDialog(this);
        progressDialog.setProgress(ProgressDialog.STYLE_SPINNER);
        progressDialog.setIndeterminate(false);
        progressDialog.setCancelable(false);
        progressDialog.setMessage("Loading...");
        progressDialog.show();
    }

    public void dismissDialog(){
        if(progressDialog!=null && progressDialog.isShowing()){
            progressDialog.dismiss();
            progressDialog=null;
        }
    }
    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    @Override
    public void onBackPressed()
    {
        Intent intent=new Intent(EditProfileActivity.this,SettingActivity_.class);
        startActivity(intent);

    }
}