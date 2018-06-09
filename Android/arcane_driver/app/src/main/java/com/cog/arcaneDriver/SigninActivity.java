package com.cog.arcaneDriver;

import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NoConnectionError;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.cog.arcaneDriver.adapter.AppController;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;
import com.mobsandgeeks.saripaar.ValidationError;
import com.mobsandgeeks.saripaar.Validator;
import com.mobsandgeeks.saripaar.annotation.Email;
import com.mobsandgeeks.saripaar.annotation.NotEmpty;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;

@EActivity (R.layout.activity_signin)
public class SigninActivity extends AppCompatActivity implements Validator.ValidationListener
{
    public final String TAG = "Signin Activity";
    String driverID,driverFirstName,driverLastName,driverEmail,driverMobile,carcategory;
    ProgressDialog progressDialog;
    Validator validator;
    String strEmail,strPassword;

    @NotEmpty(message = "")
    @Email(message = "Enter a valid email")
    @ViewById(R.id.input_email)
    EditText edtEmail;

    @NotEmpty(message = "Enter Password")
    @ViewById (R.id.input_password)
    EditText edtpassword;

    SharedPreferences.Editor editor;
    @AfterViews
    void signIn() {
    }

    @Click (R.id.btnSignin)
    void toMap()
    {
        validator.validate();
    }

    @Click (R.id.back)
    void back()
    {
        Intent intent=new Intent(this,LaunchActivity_.class);
        startActivity(intent);;
    }
        @Click(R.id.btnForgotPassword)
        void forget()
        {
            Intent intent=new Intent(this,ForgotPasswordActivity_.class);
            startActivity(intent);
        }

    @AfterViews
    void create(){
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(),getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));

        validator = new Validator(this);
        validator.setValidationListener(this);
        editor = getSharedPreferences(Constants.MY_PREFS_NAME,getApplicationContext().MODE_PRIVATE).edit();
    }

    @Override
    public void onValidationSucceeded() {

            strEmail=   edtEmail.getText().toString().trim();
            strPassword=   edtpassword.getText().toString().trim();
        showDialog();
       String url = Constants.LIVEURL+"signIn/email/"+strEmail+"/password/"+strPassword;
        System.out.println("URL is " + url);

        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {

                                JSONObject signIn_jsonobj = response.getJSONObject(i);
                             String signIn_status = signIn_jsonobj.getString("status");

                                if (signIn_status.equals("Success")) {

                                    driverID=signIn_jsonobj.optString("userid");
                                    driverFirstName=signIn_jsonobj.optString("first_name");
                                    driverLastName=signIn_jsonobj.optString("last_name");
                                    driverEmail=signIn_jsonobj.optString("email");
                                    driverMobile=signIn_jsonobj.optString("mobile");
                                    carcategory=signIn_jsonobj.optString("category");
                                    savepreferences();
                                    Toast.makeText(SigninActivity.this, "Logged in Successfully", Toast.LENGTH_SHORT).show();
                                    Intent Map=new Intent(getApplicationContext(),Map_Activity.class);
                                    Map.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    startActivity(Map);
                                     finish();
                                    dismissDialog();
                                }
                                else if (signIn_status.equals("Fail")) {
                                    //stopAnim();
                                    Toast.makeText(SigninActivity.this, "Sorry! Invalid Username and Password", Toast.LENGTH_SHORT).show();
                                    dismissDialog();
                                }

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
                if(error instanceof NoConnectionError) {
                   // stopAnim();
                    dismissDialog();
                    Toast.makeText(SigninActivity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));



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
                Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
            }
        }
    }

    public void savepreferences()
    {
        editor.putString("driverid", driverID);
        editor.putString("drivername", driverFirstName);
        editor.putString("driverphonenum", driverMobile);
        editor.putString("carcategory", carcategory);
        editor.commit();
    }



    public void showDialog(){
        progressDialog = new ProgressDialog(this,R.style.AppCompatAlertDialogStyle);
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
}
