package com.cog.arcaneDriver;


import android.app.Activity;
import android.app.ProgressDialog;
import android.content.ContentValues;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.net.Uri;
import android.os.AsyncTask;
import android.provider.MediaStore;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
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
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.bumptech.glide.Glide;
import com.cog.arcaneDriver.adapter.AppController;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;

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
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.HashMap;
import java.util.Map;


/**
 * Created by test on 12/16/16.
 */

@EActivity(R.layout.activity_upload_doc)

public class DocUpload_Activity extends AppCompatActivity {

    Uri mCapturedImageURI;
    private static final int CAMERA_REQUEST=1;
    int count;
    String picturePath,licsImg,idImg;
    ProgressDialog progressDialog;
    SharedPreferences.Editor editor;
    String[] carcategory;
    String LiveUrl="http://demo.cogzidel.com/arcane_lite/driver/";
    String driverID,driverFirstName,driverLastName,driverEmail,driverMobile,strCarCategory;
    JSONObject strJsonCategory;

    @Click (R.id.back)
    void getback()
{
   finish();
}
    @ViewById(R.id.idproofImg)
    ImageView idproof;

    @ViewById(R.id.licenceImg)
    ImageView licence;

    @ViewById(R.id.txtidproof)
    TextView txtidProof;

    @ViewById(R.id.txtidlicence)
    TextView txtidlicence;

    String imgDecodableString;

    String strEmail,strFirstName,strLastName,strPassword,strConfirmPassword,strCity,strMobile,strCountyCode,strProfileImage,strLicense,strInsurnce,strComingfrom,strFBID,strGoogleID,enCodedProImag,strSelectedCategory;


    Spinner carategory;

    @AfterViews
    void signUpImage()
    {

        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(), getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));
        final Intent i = getIntent();
        strFirstName = i.getStringExtra("FirstName");
        strLastName = i.getStringExtra("LastName");
        strPassword = i.getStringExtra("Password");
        strCity = i.getStringExtra("City");
        strMobile = i.getStringExtra("Mobile");
        strEmail= i.getStringExtra("Email");
        strCountyCode = i.getStringExtra("CountryCode");
        strProfileImage= i.getStringExtra("ProfilePicture");
        strComingfrom= i.getStringExtra("Comingfrom");
        strFBID= i.getStringExtra("FbID");
        strGoogleID= i.getStringExtra("GoogleID");

        editor = getSharedPreferences(Constants.MY_PREFS_NAME,getApplicationContext().MODE_PRIVATE).edit();

        System.out.println("DOCUMENT PAGEE " + "FNAME==" + strFirstName + " " + "LNAME==>" + strLastName + " " + "MOBILE==" + strMobile + " " + "PASSWORD==" + strPassword + " " + "CITY==" + strCity + " " + "PROFILE PICTUREE" + strProfileImage);
        getCategoryDetails();

        carategory=(Spinner)findViewById(R.id.car_category);


        carategory.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

                strSelectedCategory= parent.getItemAtPosition(position).toString();

            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });

    }



    private void getCategoryDetails()
    {
        final String url=Constants.CATEGORY_LIVE_URL + "Settings/getCategory";
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
                                strCarCategory= strJsonCategory.getString("categoryname");
                                Log.d("OUTPUT IS",strCarCategory);
                                carcategory[0]="Select car category";
                                carcategory[i+1]=strCarCategory;
                                System.out.println("CATEGORY"+carcategory[i]);
                                ArrayAdapter<String> adapter = new ArrayAdapter<String>(DocUpload_Activity.this, R.layout.spinner_item, carcategory);
                                carategory.setAdapter(adapter);
                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (error instanceof TimeoutError || error instanceof NoConnectionError) {
                    Toast.makeText(getApplicationContext(),"No net", Toast.LENGTH_SHORT).show();
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

    @Click(R.id.continuebut)
        void con()
        {

            if (licsImg == null)
            {

                android.support.v7.app.AlertDialog.Builder builder =
                        new android.support.v7.app.AlertDialog.Builder(DocUpload_Activity.this, R.style.AppCompatAlertDialogStyle);
                builder.setMessage(getString(R.string.attach_license));


                builder.setPositiveButton(getString(R.string.ok), new DialogInterface.OnClickListener() {

                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        // TODO Auto-generated method stub
                        dialog.cancel();
                    }
                });
                builder.show();




            } else if (idImg == null) {

                android.support.v7.app.AlertDialog.Builder builder =
                        new android.support.v7.app.AlertDialog.Builder(DocUpload_Activity.this, R.style.AppCompatAlertDialogStyle);
                builder.setMessage(getString(R.string.attach_id_proof));


                builder.setPositiveButton(getString(R.string.ok), new DialogInterface.OnClickListener() {

                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        // TODO Auto-generated method stub
                        dialog.cancel();
                    }
                });
                builder.show();
            }

            else if(strSelectedCategory.equals("Select car category"))
            {
                android.support.v7.app.AlertDialog.Builder builder =
                        new android.support.v7.app.AlertDialog.Builder(DocUpload_Activity.this, R.style.AppCompatAlertDialogStyle);
                builder.setMessage(getString(R.string.select_category));


                builder.setPositiveButton(getString(R.string.ok), new DialogInterface.OnClickListener() {

                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        // TODO Auto-generated method stub
                        dialog.cancel();
                    }
                });
                builder.show();

            }
            else
            {

                if(strComingfrom!=null)
                {

                    try {
                        strProfileImage= URLEncoder.encode(strProfileImage, "utf-8");
                    } catch (UnsupportedEncodingException e) {
                        e.printStackTrace();
                    }
                    if(strComingfrom.matches("facebook"))
                    {
                        faceBookLogin();
                    }
                    else if(strComingfrom.matches("google"))
                    {
                        googleSignUP();
                    }
                }
                else
                {
                    loginSuccess();
                }

            }

        }

    private void googleSignUP() {
     showDialog();
     //  http://demo.cogzidel.com/arcane_lite/driver/googleSignup/regid/5765/first_name/cogzidel/last_name/c/mobile/73376543212/email/cogzidel_new33@gmrail.com/license/yy.png/insurance/zz.png/google_id/23244
         String url= Constants.LIVEURL+"googleSignup/"+"first_name/"+strFirstName+"/last_name/"+strLastName+"/email/"+strEmail+"/regid/344444444444444"+"/google_id/"+strGoogleID+"/license/"+strLicense+"/insurance/"+strInsurnce+"/category/"+strSelectedCategory;

        System.out.println("Driver SignUp URL==>"+url);
        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {

                                JSONObject register_status= response.getJSONObject(i);

                                if(register_status.optString("status").matches("Success"))
                                {
                                    driverID=register_status.optString("userid");
                                    System.out.println("THR DRIER IS"+driverID);
                                    driverFirstName=register_status.optString("first_name");
                                    driverLastName=register_status.optString("last_name");
                                    driverEmail=register_status.optString("email");
                                    driverMobile=register_status.optString("mobile");
                                    savepreferences();
                                    saveInFirebase();
                                    Intent map=new Intent(getApplicationContext(),Map_Activity.class);
                                    map.putExtra("loginfrom","socialnetwork");
                                    map.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    startActivity(map);
                                    Toast.makeText(getApplicationContext(),"Registered Successfully",Toast.LENGTH_SHORT).show();
                                }
                                else if(register_status.optString("status_extra").matches("Exist"))
                                {
                                    driverID=register_status.optString("userid");
                                    driverFirstName=register_status.optString("first_name");
                                    driverLastName=register_status.optString("last_name");
                                    driverEmail=register_status.optString("email");
                                    driverMobile=register_status.optString("mobile");
                                    savepreferences();
                                    Intent map=new Intent(getApplicationContext(),Map_Activity.class);
                                    map.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    startActivity(map);
                                    finish();
                                    Toast.makeText(DocUpload_Activity.this, "Logged in successfully.", Toast.LENGTH_SHORT).show();
                                }
                                else if (register_status.equals("Email Already Exists."))
                                {
                                    //stopAnim();
                                    Toast.makeText(DocUpload_Activity.this, "Sorry! Email already exist.", Toast.LENGTH_SHORT).show();
                                }
                                else if (register_status.equals("Mobile Number Already Exists.")) {
                                    //stopAnim();
                                    Toast.makeText(DocUpload_Activity.this, "Sorry! Mobile Number already exist.", Toast.LENGTH_SHORT).show();
                                }
                                dismissDialog();
                            } catch (JSONException e) {
                                //stopAnim();
                                dismissDialog();
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                dismissDialog();
                //protected static final String TAG = null;
                if(error instanceof NoConnectionError) {
                    // stopAnim();
                    Toast.makeText(DocUpload_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d("DOCUMENT ACTIVITY", "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));



    }

    private void faceBookLogin() {
        showDialog();
        //final String url= Constants.LIVEURL+"fbSignup/"+"first_name/"+strFirstName+"/last_name/"+strLastName+"/email/"+strEmail+"/regid/344444444444444"+"/profile_pic/"+strProfileImage+"/fb_id/"+strFBID+"/license/"+strLicense+"/insurance/"+strInsurnce;
        final String url= Constants.LIVEURL+"fbSignup/"+"first_name/"+strFirstName+"/last_name/"+strLastName+"/email/"+strEmail+"/regid/344444444444444"+"/fb_id/"+strFBID+"/license/"+strLicense+"/insurance/"+strInsurnce+"/category/"+strSelectedCategory;
        System.out.println("Driver SignUp URL==>"+url);
        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {

                                JSONObject register_status= response.getJSONObject(i);

                                if(register_status.optString("status").matches("Success"))
                                {

                                    driverID=register_status.getString("_id");

                                    System.out.println("Uswer ID"+driverID);
                                    driverFirstName=register_status.optString("first_name");
                                    System.out.println("driverFirstName ID"+driverFirstName);

                                    driverLastName=register_status.optString("last_name");
                                    driverEmail=register_status.optString("email");
                                    driverMobile=register_status.optString("mobile");
                                    savepreferences();
                                    saveInFirebase();
                                    Intent map=new Intent(getApplicationContext(),Map_Activity.class);
                                    map.putExtra("loginfrom","socialnetwork");
                                    map.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    startActivity(map);
                                    finish();
                                    Toast.makeText(getApplicationContext(),"Registered Successfully",Toast.LENGTH_SHORT).show();
                                }
                                else if (register_status.equals("Email Already Exists."))
                                {
                                    //stopAnim();
                                    Toast.makeText(DocUpload_Activity.this, "Sorry! Email already exist.", Toast.LENGTH_SHORT).show();
                                }
                                else if (register_status.equals("Mobile Number Already Exists.")) {
                                    //stopAnim();
                                    Toast.makeText(DocUpload_Activity.this, "Sorry! Mobile Number already exist.", Toast.LENGTH_SHORT).show();
                                }
                                dismissDialog();
                            } catch (JSONException e) {
                                //stopAnim();
                                dismissDialog();
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                //protected static final String TAG = null;
                dismissDialog();
                if(error instanceof NoConnectionError) {
                    // stopAnim();
                    Toast.makeText(DocUpload_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d("DOCUMENT ACTIVITY", "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

    }

    private void loginSuccess() {
        showDialog();
        final String url= Constants.LIVEURL+"signUp/"+"first_name/"+strFirstName+"/last_name/"+strLastName+"/mobile/"+strMobile+"/country_code/"+strCountyCode+"/password/"+strPassword+"/city/"+strCity+"/email/"+strEmail+"/regid/344444444444444"+"/profile_pic/"+strProfileImage+"/license/"+strLicense+"/insurance/"+strInsurnce+"/category/"+strSelectedCategory;
        System.out.println("Driver SignUp URL==>"+url);
        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {

                                JSONObject register_status= response.getJSONObject(i);

                                if(register_status.optString("status").matches("Success"))
                                {
                                    driverID=register_status.optString("userid");
                                    driverFirstName=register_status.optString("first_name");
                                    driverLastName=register_status.optString("last_name");
                                    driverEmail=register_status.optString("email");
                                    driverMobile=register_status.optString("mobile");
                                    savepreferences();
                                    saveInFirebase();
                                    Intent map=new Intent(getApplicationContext(),Map_Activity.class);
                                    map.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    startActivity(map);
                                    finish();

                                    Toast.makeText(getApplicationContext(), "Registered Successfully", Toast.LENGTH_SHORT).show();

                                }
                                else if (register_status.equals("Email Already Exists."))
                                {
                                    //stopAnim();
                                    Toast.makeText(DocUpload_Activity.this, "Sorry! Email already exist.", Toast.LENGTH_SHORT).show();
                                }
                                else if (register_status.optString("status").matches("Fail")) {
                                    //stopAnim();
                                    Toast.makeText(DocUpload_Activity.this, "Sorry! Mobile Number already exist.", Toast.LENGTH_SHORT).show();
                                }
                                dismissDialog();
                            } catch (JSONException e) {
                                //stopAnim();
                                dismissDialog();
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                //protected static final String TAG = null;
                dismissDialog();
                if(error instanceof NoConnectionError) {
                   // stopAnim();
                    Toast.makeText(DocUpload_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d("DOCUMENT ACTIVITY", "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));
    }


    @Click({R.id.idproofImg})
    void idproofUpload(){
        count=0;
        android.support.v7.app.AlertDialog.Builder builder =
                new android.support.v7.app.AlertDialog.Builder(DocUpload_Activity.this, R.style.AppCompatAlertDialogStyle);
        builder.setMessage(getString(R.string.select_id));

        builder.setNegativeButton(getString(R.string.camera), new DialogInterface.OnClickListener() {



            @Override
            public void onClick(DialogInterface dialog, int which) {


                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.TITLE, "Image File name");
                mCapturedImageURI = getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                Intent cameraIntent = new Intent(
                        android.provider.MediaStore.ACTION_IMAGE_CAPTURE);


                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT,
                        mCapturedImageURI);
                startActivityForResult(cameraIntent, CAMERA_REQUEST);



            }
        });
        builder.setNeutralButton(getString(R.string.gallery),new DialogInterface.OnClickListener() {

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

    @Click({R.id.txtidproof})
    void txtidproofUpload(){
        count=0;
        android.support.v7.app.AlertDialog.Builder builder =
                new android.support.v7.app.AlertDialog.Builder(DocUpload_Activity.this, R.style.AppCompatAlertDialogStyle);
        builder.setMessage(getString(R.string.select_id));

        builder.setNegativeButton(getString(R.string.camera), new DialogInterface.OnClickListener() {



            @Override
            public void onClick(DialogInterface dialog, int which) {


                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.TITLE, "Image File name");
                mCapturedImageURI = getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                Intent cameraIntent = new Intent(
                        android.provider.MediaStore.ACTION_IMAGE_CAPTURE);


                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT,
                        mCapturedImageURI);
                startActivityForResult(cameraIntent, CAMERA_REQUEST);



            }
        });
        builder.setNeutralButton(getString(R.string.gallery),new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {


                Intent i = new Intent(Intent.ACTION_PICK,
                        android.provider.MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                startActivityForResult(i, 100);



            }
        });


        builder.setPositiveButton(getString(R.string.close),new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                // TODO Auto-generated method stub
                dialog.cancel();
            }
        });


        builder.show();
    }

    @Click({R.id.txtidlicence})
    void txtlicenceUpload(){
        count=1;
        android.support.v7.app.AlertDialog.Builder builder =
                new android.support.v7.app.AlertDialog.Builder(DocUpload_Activity.this, R.style.AppCompatAlertDialogStyle);
        builder.setMessage(getString(R.string.licence));

        builder.setNegativeButton(getString(R.string.camera), new DialogInterface.OnClickListener() {



            @Override
            public void onClick(DialogInterface dialog, int which) {


                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.TITLE, "Image File name");
                mCapturedImageURI = getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                Intent cameraIntent = new Intent(
                        android.provider.MediaStore.ACTION_IMAGE_CAPTURE);


                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT,
                        mCapturedImageURI);
                startActivityForResult(cameraIntent, CAMERA_REQUEST);



            }
        });
        builder.setNeutralButton(getString(R.string.gallery),new DialogInterface.OnClickListener() {

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


    @Click({R.id.licenceImg})
    void licenceUpload(){
        count=1;
        android.support.v7.app.AlertDialog.Builder builder =
                new android.support.v7.app.AlertDialog.Builder(DocUpload_Activity.this, R.style.AppCompatAlertDialogStyle);
        builder.setMessage(getString(R.string.licence));

        builder.setNegativeButton(getString(R.string.camera), new DialogInterface.OnClickListener() {



            @Override
            public void onClick(DialogInterface dialog, int which) {


                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.TITLE, "Image File name");
                mCapturedImageURI = getContentResolver().insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                Intent cameraIntent = new Intent(
                        android.provider.MediaStore.ACTION_IMAGE_CAPTURE);


                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT,
                        mCapturedImageURI);
                startActivityForResult(cameraIntent, CAMERA_REQUEST);



            }
        });
        builder.setNeutralButton(getString(R.string.gallery),new DialogInterface.OnClickListener() {

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


    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == CAMERA_REQUEST && resultCode == RESULT_OK) {

            String selectedImagePath = getRealPathFromURI(mCapturedImageURI);

            picturePath= selectedImagePath;

            if (count == 0) {
                idproof.setScaleType(ImageView.ScaleType.FIT_XY);
                Glide.with(getApplicationContext()).load(picturePath).skipMemoryCache(true).into(idproof);

            }else {

                licence.setScaleType(ImageView.ScaleType.FIT_XY);
                Glide.with(getApplicationContext()).load(picturePath).skipMemoryCache(true).into(licence);


            }
     new ImageuploadTask(this).execute();
        }

        System.out.println("Request Code+requestCode" + "Result Code" + resultCode + "data" + data);

        if (requestCode == 100 && resultCode == Activity.RESULT_OK
                && null != data) {


            if (count == 0) {
                System.out.println("The count is Zero " + count);


                Uri selectedImage = data.getData();
                String[] filePathColumn = { MediaStore.Images.Media.DATA };

                // Get the cursor
                Cursor cursor = getContentResolver().query(selectedImage,
                        filePathColumn, null, null, null);
                // Move to first row
                cursor.moveToFirst();

                int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
                picturePath = cursor.getString(columnIndex);
                cursor.close();

                // Set the Image in ImageView after decoding the String
                idproof.setScaleType(ImageView.ScaleType.FIT_XY);
                Glide.with(getApplicationContext()).load(picturePath).skipMemoryCache(true).into(idproof);
                new ImageuploadTask(this).execute();

            } else {
                Uri selectedImage = data.getData();
                String[] filePathColumn = { MediaStore.Images.Media.DATA };

                // Get the cursor
                Cursor cursor = getContentResolver().query(selectedImage,
                        filePathColumn, null, null, null);
                // Move to first row
                cursor.moveToFirst();

                int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
                picturePath = cursor.getString(columnIndex);
                cursor.close();

                // Set the Image in ImageView after decoding the String
                licence.setScaleType(ImageView.ScaleType.FIT_XY);
                Glide.with(getApplicationContext()).load(picturePath).skipMemoryCache(true).into(licence);

               new ImageuploadTask(this).execute();
            }

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
        private DocUpload_Activity activity;

        public ImageuploadTask(DocUpload_Activity activity)
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

            String urlServer=Constants.LIVEURL+"imageUpload";
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
            if(count==0)
            {
                licsImg=Str1_imageurl.trim();
                System.out.println("Licence==="+licsImg);
                JSONArray array = new JSONArray(licsImg);
                JSONObject jsonObj  = array.getJSONObject(0);
                System.out.println("image name"+jsonObj.getString("image_name"));
                strLicense=jsonObj.optString("image_name");
                System.out.println("License===>"+strLicense);
            }
            else
            {
                idImg=Str1_imageurl.trim();
                System.out.println("Insurance===="+idImg);
                JSONArray array = new JSONArray(idImg);
                JSONObject jsonObj  = array.getJSONObject(0);
                System.out.println("image name"+jsonObj.getString("image_name"));
                strInsurnce=jsonObj.optString("image_name");
                System.out.println("Insurenace===>"+strInsurnce);
            }


        }
        catch(Exception e){

            e.printStackTrace();

        }
    }
    public void savepreferences()
    {
        editor.putString("driverid", driverID);
        editor.putString("drivername", driverFirstName);
        editor.putString("driverphonenum", driverMobile);
        editor.putString("carcategory", strSelectedCategory);
        editor.commit();
    }

    public void saveInFirebase()
    {
        if(driverID!=null && !driverID.isEmpty())
        {
         DatabaseReference ref = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverID);
         Map<String, Object> updates = new HashMap<String, Object>();
            updates.put("name",driverFirstName);
            updates.put("proof_status","Pending");    //proofstatus

            Map<String, Object> updateaccept= new HashMap<String, Object>();
            updateaccept.put("status","");
            updateaccept.put("trip_id","");
            updates.put("accept",updateaccept);

            Map<String, Object> updaterequest= new HashMap<String, Object>();
            updaterequest.put("req_id","");
            updaterequest.put("status","");
            updates.put("request",updaterequest);

            ref.setValue(updates, new DatabaseReference.CompletionListener()
            {
                @Override
                public void onComplete(DatabaseError databaseError, DatabaseReference databaseReference) {
                    System.out.println("DATA SAVED SUCCESSFULLY");
                    if(databaseError!=null){
                        System.out.println("DATA SAVED SUCCESSFULLY");
                    }
                }
            });
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

    public void dismissDialog()
    {
        if(progressDialog!=null && progressDialog.isShowing())
        {
            progressDialog.dismiss();
            progressDialog=null;
        }
    }

    }

