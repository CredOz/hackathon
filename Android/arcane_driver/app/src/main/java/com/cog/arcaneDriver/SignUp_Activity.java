package com.cog.arcaneDriver;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Build;
import android.support.v7.app.AppCompatActivity;
import android.text.InputFilter;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NoConnectionError;
import com.android.volley.Response;
import com.android.volley.TimeoutError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.cog.arcaneDriver.adapter.AppController;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;
import com.google.i18n.phonenumbers.NumberParseException;
import com.google.i18n.phonenumbers.PhoneNumberUtil;
import com.google.i18n.phonenumbers.Phonenumber;
import com.mobsandgeeks.saripaar.ValidationError;
import com.mobsandgeeks.saripaar.Validator;
import com.mobsandgeeks.saripaar.annotation.ConfirmPassword;
import com.mobsandgeeks.saripaar.annotation.Email;
import com.mobsandgeeks.saripaar.annotation.Length;
import com.mobsandgeeks.saripaar.annotation.NotEmpty;
import com.mobsandgeeks.saripaar.annotation.Password;
import com.rengwuxian.materialedittext.MaterialEditText;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.List;

@EActivity(R.layout.activity_signup)

public class SignUp_Activity extends AppCompatActivity implements Validator.ValidationListener {

    Boolean statusEmail=false;
    Boolean statusMobile=false;
    ProgressDialog progressDialog;

    @NotEmpty(message = "")
    @Email(message = "Enter a valid email")
    @ViewById (R.id.input_email)
    EditText edtEmail;

    @NotEmpty(message = "")
    @Length (min=3, message = "Minimum of 3 characters")
    @ViewById (R.id.input_name_first)
    EditText edtFirstName;

    @NotEmpty(message = "")
    @Length (min=3,message = "Minimum of 3 characters")
    @ViewById (R.id.input_name_last)
    EditText edtLastName;

    @NotEmpty(message = "Enter Mobile Number")
    @ViewById (R.id.input_mobile)
    EditText edtMobile;

    @NotEmpty(message = "")
    @Length(min=8, message="Enter a minimum of 8 characters")
    @ViewById (R.id.input_password)
    @Password(message = "")
    EditText edtpassword;

    @NotEmpty(message = "")
   // @Length(min=8, message="Enter a minimum of 8 characters")
    @ConfirmPassword(message="Password does not match")
    @ViewById (R.id.input_conform_password)
    EditText edtConPassword;

    @NotEmpty(message = "")
    @Length(min=1, message="Enter City")
    @ViewById (R.id.input_city)
    EditText edtCity;


    @NotEmpty(message = "")
    @ViewById(R.id.input_country_code)
    MaterialEditText userCountryCode;


    Validator validator;
    String strEmail,strFirstName,strLastName,strPassword,strConfirmPassword,strCity,strMobile,strCountyCode;
    CountryCodePicker ccp;


    @Click (R.id.back)
    void back()
    {
       finish();
    }

    @Click (R.id.signIn)
    void signin()
    {
        Intent signin=new Intent(this,SigninActivity_.class);
        startActivity(signin);
    }

    @Click (R.id.continueReg)
    void con()
    {
        validator.validate();
    }

    @Click(R.id.input_country_code)
    void countryCode()
    {
        CountryCodeDialog.openCountryCodeDialog(ccp);//Open country code dialog
    }




    @AfterViews
    void create(){
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(),getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));

        ccp =(CountryCodePicker)findViewById(R.id.ccp);

        ccp.setOnCountryChangeListener(new CountryCodePicker.OnCountryChangeListener() {
            @Override
            public void onCountrySelected() {
                userCountryCode.setText(ccp.getSelectedCountryCodeWithPlus());
            }
        });

        validator = new Validator(this);
        validator.setValidationListener(this);

    }

    @Override
    public void onValidationSucceeded()
    {

        strEmail=   edtEmail.getText().toString().trim();
        strFirstName=   edtFirstName.getText().toString().trim();
        strLastName=   edtLastName.getText().toString().trim();
        strPassword=   edtpassword.getText().toString().trim();
        strConfirmPassword=   edtConPassword.getText().toString().trim();
        strCity=   edtCity.getText().toString().trim();
        strMobile=edtMobile.getText().toString();

        strFirstName=strFirstName.replaceAll(" ", "%20");
        strPassword=strPassword.replaceAll(" ", "%20");
        strLastName=strLastName.replaceAll(" ","%20");
        strCity=strCity.replaceAll(" ","%20");
        strMobile=strMobile.replaceAll(" ","%20");
//        strCountyCode=strCountyCode.replaceAll("\\+","%2B");
        strCountyCode=userCountryCode.getText().toString();
        try {
            strPassword  = URLEncoder.encode(strPassword, "utf-8");//Encode before sending to Webservice
        } catch (UnsupportedEncodingException e) {
            //TODO: handle
            e.printStackTrace();
        }

       /* if(strMobile.length() < 10 || strMobile.charAt(0) == '0' && strMobile.charAt(1) == '0' && strMobile.charAt(2) == '0')
        {
            edtMobile.setError("Enter a valid number");
        }*/
        if (!validatePhone()) {

        }
        if (!validateCountryCode()) {

        }else if (!validateUsing_libphonenumber()) {
            edtMobile.setError(getString(R.string.invalid_mobile_number));
        } else {
          //  loginEmail();
            loginPhone();
        }


    }

    private void loginPhone()
    {
        showDialog();
        final String url= Constants.LIVEURL+"checkEmailPhone/"+"email/"+strEmail+"/mobile/"+strMobile;
        System.out.println("Driver SignUp URL==>"+url);

        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        if(response.length() > 0) {
                            for (int i = 0; i < response.length(); i++)
                            {

                                try {

                                    JSONObject register_jsonobj = response.getJSONObject(i);

                                    if(register_jsonobj.optString("status").matches("Success"))
                                    {

                                        dismissDialog();
                                        Intent editIntent=new Intent(SignUp_Activity.this,ImageUpload_.class);
                                        editIntent.putExtra("FirstName",strFirstName);
                                        editIntent.putExtra("LastName", strLastName);
                                        editIntent.putExtra("Email",strEmail);
                                        editIntent.putExtra("Password",strPassword);
                                        editIntent.putExtra("Mobile",strMobile);
                                        editIntent.putExtra("City",strCity);
                                        editIntent.putExtra("CountryCode", strCountyCode);
                                        startActivity(editIntent);
                                    }
                                    else if(register_jsonobj.optString("message").matches("Mobile exist"))
                                    {
                                        System.out.println("Mobile number Already exists");
                                        edtMobile.setError("Mobile Number already exists");
                                        dismissDialog();
                                    }
                                    else if(register_jsonobj.optString("message").matches("Email exist"))
                                    {
                                        System.out.println("Email Already exists");
                                        edtEmail.setError("Email already exists");
                                         dismissDialog();
                                    }
                                    else if(register_jsonobj.optString("message").matches("Both Exits"))
                                    {
                                        System.out.println("Both Already exists");
                                        edtMobile.setError("Mobile Number already exists");
                                        edtEmail.setError("Email already exists");
                                        dismissDialog();
                                    }

                                } catch (JSONException e) {

                                    dismissDialog();
                                    e.printStackTrace();
                                }

                            }
                        }
                        else
                        {

                        }
                    }
                },
                new Response.ErrorListener()
                {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        if (error instanceof TimeoutError || error instanceof NoConnectionError) {
                            dismissDialog();
                        }
                    }
                });
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

    }

    private void loginEmail()

    {
      //  showDialog();
        final String url= Constants.LIVEURL+"emailExist/"+"email/"+strEmail;
        System.out.println("Driver SignUp URL==>" + url);

        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        if(response.length() > 0) {
                            for (int i = 0; i < response.length(); i++) {

                                try {

                                    JSONObject register_jsonobj = response.getJSONObject(i);

                                    if(register_jsonobj.optString("status").matches("Success"))
                                    {
                                       // dismissDialog();
                                        statusEmail=true;


                                    }
                                    else if(register_jsonobj.optString("status").matches("Fail"))
                                    {
                                       // dismissDialog();
                                        edtEmail.setError("Email already exists");
                                        System.out.println("Email Already exists");
                                    }

                                } catch (JSONException e) {

                                   // dismissDialog();
                                    e.printStackTrace();
                                }

                            }
                        }else {
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        if (error instanceof TimeoutError || error instanceof NoConnectionError) {
                           // dismissDialog();
                        }
                    }
                });
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

    private boolean validateCountryCode() {

        if (userCountryCode.getText().toString().trim().isEmpty()) {
            userCountryCode.setError("");
            edtMobile.setError(getString(R.string.enter_country_code));
            return false;
        } else if (userCountryCode.getText().toString().equals("CC")) {
            edtMobile.setError(getString(R.string.enter_country_code));
            userCountryCode.setError("");
            return false;
        } else {
            userCountryCode.setError(null);
        }
        return true;
    }

    private boolean validatePhone() {

        if(edtMobile.getText().toString().trim().isEmpty()) {
            edtMobile.setError(getString(R.string.enter_mobile_number));
            return false;
        }
        else if (userCountryCode.getText().toString().trim().isEmpty())
        {
            return false;
        }
        else  if (!edtMobile.getText().toString().trim().isEmpty())
        {
            if (edtMobile.getText().toString().substring(0, 1).matches("0")) {
                edtMobile.setError("Enter a valid number");
                return false;
            } else {
                int maxLengthofEditText = 15;
                edtMobile.setFilters(new InputFilter[]{new InputFilter.LengthFilter(maxLengthofEditText)});
                edtMobile.setError(null);
            }
            return true;
        }

        return true;
    }

    private boolean validateUsing_libphonenumber() {
        if(edtMobile.getText().toString().length()<=1){
            return false;
        }
        else  if (edtMobile.getText().toString().substring(0, 1).matches("0")) {
            edtMobile.setError("Enter a valid number");
            return false;
        }
        else
        {
            strCountyCode = userCountryCode.getText().toString();
            strMobile = edtMobile.getText().toString();
            if (validatePhone() && validateCountryCode()) {
                System.out.println("CountryCode==>" + strCountyCode);
                if (Build.VERSION.SDK_INT < Build.VERSION_CODES.LOLLIPOP) {
                    strCountyCode = strCountyCode.replace("+", "");
                }
                System.out.println("SDK_VERSION==>" + Build.VERSION.SDK_INT);
                System.out.println("SDK_VERSION_RELEASE" + Build.VERSION.RELEASE);
                System.out.println("CountryCode1==>" + strCountyCode);
                PhoneNumberUtil phoneNumberUtil = PhoneNumberUtil.getInstance();
                String isoCode = phoneNumberUtil.getRegionCodeForCountryCode(Integer.parseInt(strCountyCode));
                Phonenumber.PhoneNumber phoneNumber = null;

                try {
                    //phoneNumber = phoneNumberUtil.parse(phNumber, "IN");  //if you want to pass region code
                    phoneNumber = phoneNumberUtil.parse(strMobile, isoCode);
                } catch (NumberParseException e) {
                    System.err.println(e);
                }

                boolean isValid = phoneNumberUtil.isValidNumber(phoneNumber);
                if (isValid) {
                    String internationalFormat = phoneNumberUtil.format(phoneNumber, PhoneNumberUtil.PhoneNumberFormat.INTERNATIONAL);
                    return true;
                } else {
                    edtMobile.setError(getString(R.string.enter_a_valid_mobile_number));
                    return false;
                }
            }
            return true;
        }

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
