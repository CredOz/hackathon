package com.cog.arcaneDriver;

import android.annotation.TargetApi;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Build;
import android.support.v7.app.AppCompatActivity;
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

import java.text.DateFormat;
import java.text.DecimalFormat;
import java.util.Date;

@EActivity(R.layout.activity_earning)
public class EarningActivity extends AppCompatActivity {
    String tripTime,driverId=null,earnings;
 SharedPreferences state;
    @Click(R.id.back)
    void back()
    {
        Intent intent=new Intent(this,Map_Activity.class);
        startActivity(intent);;
    }


    @ViewById(R.id.trip_amount)
    TextView earningamount;

    @ViewById(R.id.timetrip)
    TextView lastTripTime;


    @AfterViews
    void create(){
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(),getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));
        earningamount.setHorizontallyScrolling(true);

        SharedPreferences prefs = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
        driverId = prefs.getString("driverid", null);

        getEarning();
    }


    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    @Override
    public void onBackPressed()
    {
        Intent intent=new Intent(EarningActivity.this,Map_Activity.class);
        startActivity(intent);

    }

    public void getEarning() {

        String url = Constants.LIVEURL + "yourEarnings/userid/" + driverId;
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
                                earnings = signIn_jsonobj.getString("total_price");
                                tripTime = signIn_jsonobj.getString("last_tripDate");

                                if(earnings!=null&&!earnings.isEmpty())
                                {
                                    Double amount=Double.parseDouble(earnings);
                                    earningamount.setText("$"+new DecimalFormat("#.#").format(amount));
                                    System.out.println("THE AMOUNT IS"+new DecimalFormat("#.#").format(amount));
                                }
                                else{
                                    earningamount.setText("$0");
                                }

                                if(tripTime!=null&&!tripTime.isEmpty()){
                                    long time=Long.parseLong(tripTime);
                                    lastTripTime.setText(getCurrentdate(time));
                                }
                                else
                                {
                                    lastTripTime.setText("NIL");
                                }



                            } catch (JSONException e) {
                                //stopAnim();
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



    public  String getCurrentdate(long timestamp) {
        try{
            //DateFormat sdf1 = new SimpleDateFormat("dd/MM/yyyy");
            Date netDate = (new Date(timestamp* 1000L));
            return DateFormat.getDateTimeInstance().format(netDate);
        }catch (Exception e) {
        }
        return "";
    }


}
