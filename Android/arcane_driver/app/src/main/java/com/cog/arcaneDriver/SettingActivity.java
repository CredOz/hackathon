package com.cog.arcaneDriver;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Build;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NoConnectionError;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.cog.arcaneDriver.adapter.AppController;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;
import com.cog.arcaneDriver.adapter.RoundImageTransform;
import com.firebase.geofire.GeoLocation;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.FirebaseDatabase;
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

@EActivity(R.layout.activity_setting)
public class SettingActivity extends AppCompatActivity {

    Validator validator;
    public String userID, firstName, lastName, email, mobileNumber, countryCode, profileImage, profileImageNew, status, message,strCategory,drivercarcategory;
    private static final int CAMERA_CAPTURE_IMAGE = 100;
    public static final int MEDIA_TYPE_IMAGE = 1;
    String picturePath,profImage;
    ProgressDialog progressDialog;
    GeoFire geoFire;
    SharedPreferences.Editor editor;
    TextView companyName,productName,licenseName;

    private Uri fileUri; // file url to store image/video

    @ViewById(R.id.profileImage)
    ImageView edtProfileImage;

    @ViewById(R.id.backButton)
    ImageButton backButton;

    @ViewById(R.id.editButton)
    ImageButton editButton;

    @ViewById(R.id.editCancelButton)
    ImageButton editCancel;


    @ViewById(R.id.save_button)
    Button saveButton;

    @NotEmpty (message = "Enter First Name")
    @Length (max = 15)
    @ViewById(R.id.edtFirstName)
    EditText inputFirstName;

    @NotEmpty(message = "Enter Last Name")
    @Length(max = 15)
    @ViewById(R.id.edtLastName)
    EditText inputLastName;

    @NotEmpty
    @ViewById(R.id.edtCountryCode)
    EditText inputCountryCode;

    @NotEmpty
    @ViewById(R.id.edtMobile)
    EditText inputMobileNumber;

    @ViewById(R.id.edtEmail)
    EditText inputEmail;

    @ViewById(R.id.carcategory)
    EditText carcategory;


    @ViewById(R.id.ccp)
    CountryCodePicker ccp;

    @AfterViews
    void settingsActivity() {
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(), getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));

        SharedPreferences prefs = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
        userID = prefs.getString("driverid", null);
        strCategory= prefs.getString("carcategory", null);
        System.out.println("UserID in settings" + userID+strCategory);
        // setup GeoFire with category
        if(strCategory!=null && !strCategory.isEmpty()){
            geoFire = new GeoFire(FirebaseDatabase.getInstance().getReference().child("drivers_location").child(strCategory));
        }
        else{
            geoFire = new GeoFire(FirebaseDatabase.getInstance().getReference().child("drivers_location"));
        }

        companyName = (TextView) findViewById(R.id.companyName);
        productName = (TextView) findViewById(R.id.companyProduct);
        licenseName= (TextView) findViewById(R.id.licencseName);
        companyName.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(Intent.ACTION_VIEW,
                        Uri.parse(getResources().getString(R.string.companyLink)));
                startActivity(i);
            }
        });
        productName.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(Intent.ACTION_VIEW,
                        Uri.parse(getResources().getString(R.string.productLink)));
                startActivity(i);
            }
        });
        licenseName.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(Intent.ACTION_VIEW,
                        Uri.parse(getResources().getString(R.string.licenseLink)));
                startActivity(i);
            }
        });
        //Change Font to Whole View
        displayDetails();
    }

    @Click(R.id.logout_button)
    void logout()
    {
        android.support.v7.app.AlertDialog.Builder builder =
                new android.support.v7.app.AlertDialog.Builder(SettingActivity.this, R.style.AppCompatAlertDialogStyle);
        builder.setTitle(getString(R.string.logout_header));
        builder.setMessage(getString(R.string.logout_msg));
        builder.setCancelable(false);
        builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {

                geoFire.offlineLocation(userID, new GeoLocation(0.0,0.0), new GeoFire.CompletionListener() {
                    @Override
                    public void onComplete(String key, DatabaseError error)
                    {
                        if (error != null)
                        {
                            System.out.println("Location not saved on server successfully!");
                        } else
                        {
                            System.out.println("Location saved on server successfully!");
                        }
                    }
                });
                gooffline();
            }



        });
        builder.setNegativeButton(R.string.no, new DialogInterface.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {


                dialog.dismiss();
            }
            //  alertdialog2.cancel();

        });

        builder.show();
    }

    private void clearPreference()
    {
        SharedPreferences settings = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
        SharedPreferences.Editor editor = settings.edit();
        editor.clear();
        editor.commit();

        Intent intent=new Intent(SettingActivity.this,LaunchActivity_.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }

    @Click(R.id.editButton)
    void editProfile()
    {
        Intent i=new Intent(SettingActivity.this,EditProfileActivity_.class);
        i.putExtra("firstName",firstName);
        i.putExtra("lastName",lastName);
        i.putExtra("email",email);
        i.putExtra("mobileNumber",mobileNumber);
        i.putExtra("profileimage",profileImage);
        i.putExtra("carcategory",drivercarcategory);
        i.putExtra("coutrycode",countryCode);
        startActivity(i);
        finish();

    }

    @Click(R.id.backButton)
    void goBack(){
        Intent intent=new Intent(SettingActivity.this,LaunchActivity_.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
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
                            drivercarcategory=jsonObject.optString("category");
//                            savepreferences();


                            try
                            {
                                inputFirstName.setText(firstName.replaceAll("%20"," "));
                                inputLastName.setText(lastName.replaceAll("%20"," "));
                                inputEmail.setText(email);
                                inputMobileNumber.setText(mobileNumber);
                                inputCountryCode.setText(countryCode);
                                carcategory.setText(drivercarcategory);
                                Glide.with(SettingActivity.this)
                                        .load(profileImage)
                                        .diskCacheStrategy(DiskCacheStrategy.NONE)
                                        .skipMemoryCache(true)
                                        .transform(new RoundImageTransform(SettingActivity.this))
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


    public void gooffline()

    {
        showDialog();

        String url = Constants.LIVEURL + "updateOnlineStatus/userid/" + userID + "/online_status/0" ;
        System.out.println(" ONLINE URL is " + url);

        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++)
                        {
                            try
                            {
                                JSONObject signIn_jsonobj = response.getJSONObject(i);
                                String signIn_status = signIn_jsonobj.getString("status");

                                if (signIn_status.equals("Success"))

                                {
                                    clearPreference();

                                }
                                else if (signIn_status.equals("Fail"))
                                {
                                    //stopAnim();
                                }

                            }
                            catch (JSONException e) {
                                //stopAnim();

                                e.printStackTrace();
                            }

                        }
                        dismissDialog();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                dismissDialog();
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    //
                    //    Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d("Setting Activity", "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

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
    @Override
    protected void onDestroy()
    {
    // Clear
      Glide.clear(edtProfileImage);
      super.onDestroy();
    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    @Override
    public void onBackPressed()
    {
        Intent intent=new Intent(SettingActivity.this,Map_Activity.class);
        startActivity(intent);

    }
}