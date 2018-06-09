package com.cog.arcaneDriver;

import android.annotation.TargetApi;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.ViewGroup;
import android.widget.TextView;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NoConnectionError;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.cog.arcaneDriver.adapter.AppController;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ViewById;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

@EActivity(R.layout.activity_rating)
public class RatingActivity extends AppCompatActivity
{
    FlexibleRatingBar driverRatingBar;
    String driverId,totalearnings;
    ProgressDialog progressDialog;

    @Click(R.id.back)
    void back()
    {
        Intent intent=new Intent(this,Map_Activity.class);
        startActivity(intent);;
    }

    @ViewById(R.id.content_Txt)
    TextView contentTxt;

    @AfterViews
    void create() {
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(), getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));

        driverRatingBar=(FlexibleRatingBar)findViewById(R.id.flexibleRatingBar);


        SharedPreferences prefs = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
        driverId = prefs.getString("driverid", null);

        getOverallRating();
    }

    public void getOverallRating()
    {
        showDialog();
        String url = Constants.LIVEURL + "overallRating/userid/" + driverId;
        System.out.println(" ONLINE URL is " + url);

        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {
                                JSONObject signIn_jsonobj = response.getJSONObject(i);
                                if(signIn_jsonobj.getString("status").matches("Success"))
                                {

                                    totalearnings = signIn_jsonobj.getString("total_star");
                                    Log.d("Rating", "your selected value is:" + totalearnings);
                                    Float totalrating=Float.parseFloat(totalearnings);
                                    driverRatingBar.setRating(totalrating);
                                    dismissDialog();
                                    if(totalrating<=3)
                                    {
                                        contentTxt.setText(R.string.low_rating);
                                    }
                                    else if(totalrating>3)
                                    {
                                        contentTxt.setText(R.string.rating_content);
                                    }
                                }
                                else
                                {
                                    dismissDialog();
                                    contentTxt.setText(R.string.no_rating_yet);
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
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    //
                    //    Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d("Error", "EarningActivity: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    @Override
    public void onBackPressed()
    {
        Intent intent=new Intent(RatingActivity.this,Map_Activity.class);
        startActivity(intent);

    }

    public void showDialog()
    {
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
