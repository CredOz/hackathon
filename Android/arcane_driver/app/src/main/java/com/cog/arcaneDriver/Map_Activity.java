package com.cog.arcaneDriver;

import android.Manifest;
import android.animation.ObjectAnimator;
import android.annotation.TargetApi;
import android.app.Dialog;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.ProgressDialog;
import android.content.ComponentName;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.ServiceConnection;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationManager;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.os.Looper;
import android.support.annotation.IdRes;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.NotificationCompat;
import android.support.v4.content.ContextCompat;
import android.support.v4.graphics.drawable.RoundedBitmapDrawable;
import android.support.v4.graphics.drawable.RoundedBitmapDrawableFactory;
import android.support.v7.widget.CardView;
import android.text.TextUtils;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.BounceInterpolator;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RatingBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.akexorcist.googledirection.DirectionCallback;
import com.akexorcist.googledirection.GoogleDirection;
import com.akexorcist.googledirection.constant.TransportMode;
import com.akexorcist.googledirection.model.Direction;
import com.akexorcist.googledirection.util.DirectionConverter;
import com.akhgupta.easylocation.EasyLocationAppCompatActivity;
import com.akhgupta.easylocation.EasyLocationRequest;
import com.akhgupta.easylocation.EasyLocationRequestBuilder;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.NoConnectionError;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.androidadvance.topsnackbar.TSnackbar;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.load.resource.drawable.GlideDrawable;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.BitmapImageViewTarget;
import com.bumptech.glide.request.target.GlideDrawableImageViewTarget;
import com.cog.arcaneDriver.adapter.AppController;
import com.cog.arcaneDriver.adapter.Constants;
import com.cog.arcaneDriver.adapter.FontChangeCrawler;
import com.cog.arcaneDriver.adapter.RoundImageTransform;
import com.firebase.geofire.GeoLocation;
import com.firebase.geofire.GeoQueryEventListener;
import com.google.android.gms.appindexing.Action;
import com.google.android.gms.appindexing.AppIndex;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.LocationSource;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.CameraPosition;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MapStyleOptions;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.firebase.FirebaseApp;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;
import com.roughike.bottombar.BottomBar;
import com.roughike.bottombar.OnTabSelectListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.text.DateFormat;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Timer;
import java.util.TimerTask;
import java.util.concurrent.TimeUnit;

public class Map_Activity extends EasyLocationAppCompatActivity implements OnMapReadyCallback, GoogleApiClient.ConnectionCallbacks, LocationSource,
        GoogleApiClient.OnConnectionFailedListener, LocationListener, GeoQueryEventListener, ValueEventListener, DirectionCallback {

    private static final String TAG = "MapActivity";

    private GoogleMap mMap;
    GoogleApiClient mGoogleApiClient;
    protected LocationRequest mLocationRequest;
    private boolean mRequestingLocationUpdates = false;
    public static final long UPDATE_INTERVAL_IN_MILLISECONDS = 2000;
    LocationService myService, locationService;
    String tripState, onlinecheck;
    static boolean distanceStatus;
    LocationManager locationManager;
    Location mCurrentLocation, lStart, lEnd;
    static double distance = 0;
    double speed;
    Button start, pause, stop;
    static long startTime, endTime;
    ImageView image;
    static ProgressDialog locate;
    static int p = 0;
    int previousToll=0;
    RatingBar rateBar;
    String mLastUpdateTime, strDistanceBegin, currentDateTimeString;
    private boolean mResolvingError = false;
    private static final int REQUEST_RESOLVE_ERROR = 1001;
    FlexibleRatingBar flexibleRatingBar;
    String ratingInt;
    private OnLocationChangedListener mMapLocationListener = null;

    Bitmap mapBitmap;
    ArrayList<LatLng> MarkerPoints;
    ValueEventListener listener, tripListener, ratingListener,prooflistener;
    ObjectAnimator animY;
    DatabaseReference requestReference, tripReference,proofstatusref;
    // Keys for storing activity state in the Bundle.
    protected final static String REQUESTING_LOCATION_UPDATES_KEY = "requesting-location-updates-key";
    protected final static String LOCATION_KEY = "location-key";
    protected final static String LAST_UPDATED_TIME_STRING_KEY = "last-updated-time-string-key";
    private static final int MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION = 1;
    /**
     * The fastest rate for active location updates. Exact. Updates will never be more frequent
     * than this value.
     */
    Boolean isMarkerRotating = false;
    SharedPreferences.Editor editor, getState;
    SharedPreferences state;
    public static final long FASTEST_UPDATE_INTERVAL_IN_MILLISECONDS = UPDATE_INTERVAL_IN_MILLISECONDS / 2;
    Dialog d, dialogTripSummary, dialog;
    RelativeLayout onlineLay, toolbarLayout;
    boolean clicked = false;
    String driverId = null, onlinestatus, drivername, strTotalDistance, savedState;
    FirebaseApp app;
    private static final String GEO_FIRE_DB = Constants.FIREBASE_URL;
    private static final String GEO_FIRE_REF = GEO_FIRE_DB + "/drivers_location";
    GeoFire geoFire;
    DatabaseReference ratingReference, ratingRef, checkAccepRef,checkTripCancelRef;
    DatabaseReference childRef;
    public String riderFirstName, riderLastName, status, message, ridermobile;
    BottomBar bottomBar;
    DatabaseReference listRef;

    RelativeLayout startTripLayout, completeTripLayout, completeRatingLayout, arriveNowLayout, endTripLayout, destinationLayout;
    TextView onlineTxt, txtPickUp, txtRiderName, txtRiderDestination, txtRiderName_Begin, txtEndTrip, txtDestination,toll_pay;
    Button btnArriveNow, btnEndTrip;
    // location accuracy settings
    private static final LocationRequest REQUEST = LocationRequest.create()
            .setPriority(LocationRequest.PRIORITY_BALANCED_POWER_ACCURACY);
    List<Location> markers = new ArrayList<Location>();
    Marker marker, marker1;
    LatLng destLocation, orginLocation;
    ImageButton riderinfo,riderinfoinarrived;

    Button btnStartTrip, btnCompleteTrip, btnCompleteRating;
    String reqID, tripID, strLat, strLng;
    String strsetValue, strStart, strEnd, tripStatus, strTotalPrice;

    Animation an2;
    RelativeLayout progressLayout1, progresslayout, FAB;
    ProgressWheel pwOne;
    ImageView requestMapView;
    View mapView;
    String tollfee,totalprice;
    String riderID, strsetdestination, strRiderProfile,strCategory;
    /**
     * ATTENTION: This was auto-generated to implement the App Indexing API.
     * See https://g.co/AppIndexing/AndroidStudio for more information.
     * rank list US Is india regnonized
     */
    private GoogleApiClient client;
    boolean doubleBackToExitPressedOnce = false;
    ProgressDialog progressDialog;
    EasyLocationRequest easyLocationRequest;
    String strWebCategory,strWebprice_km,strwebpricepermin,strwebmaxsize,strwebpricefare,strCacnelStatus;

    TextView trip_rider_name,txtTotalDistance,txtTripAmount,txtTripdate;
    ImageView imgRiderProfile;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_map_);
        // Obtain the SupportMapFragment and get notified when the map is ready to be used.
        getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
        FontChangeCrawler fontChanger = new FontChangeCrawler(getAssets(), getString(R.string.app_font));
        fontChanger.replaceFonts((ViewGroup) this.findViewById(android.R.id.content));
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapView = mapFragment.getView();
        mapFragment.getMapAsync(this);
        onlineLay = (RelativeLayout) findViewById(R.id.onlinelay);
        progressLayout1 = (RelativeLayout) findViewById(R.id.progress_layout);
        onlineTxt = (TextView) findViewById(R.id.onlineTxt);
        toll_pay=(TextView) findViewById(R.id.toll_pay);
        editor = getSharedPreferences(Constants.MY_PREFS_NAME, getApplicationContext().MODE_PRIVATE).edit();

        getState = getSharedPreferences(Constants.MY_STATE_KEY, getApplicationContext().MODE_PRIVATE).edit();
        state = getSharedPreferences(Constants.MY_STATE_KEY, MODE_PRIVATE);
        tripState = state.getString("tripstate", null);
        if (tripState == null) {
            //set end click to get request first time
            tripState = "endClicked";
        }


        //txtPickUp=(TextView)findViewById(R.id.txtPickUp_Begin);
        //   rateBar=(RatingBar)findViewById(R.id.ratingBar);
        toolbarLayout = (RelativeLayout) findViewById(R.id.toolbar);
        destinationLayout = (RelativeLayout) findViewById(R.id.destinationLayout);
        txtRiderDestination = (TextView) findViewById(R.id.txtDestination);
        txtRiderDestination.setEllipsize(TextUtils.TruncateAt.MARQUEE);
        txtRiderDestination.setSelected(true);
        arriveNowLayout = (RelativeLayout) findViewById(R.id.arrive_layout);
        txtRiderName = (TextView) findViewById(R.id.txtRiderName);
        btnArriveNow = (Button) findViewById(R.id.btnArriveNow);
        startTripLayout = (RelativeLayout) findViewById(R.id.begin_start__layout);
        txtRiderName_Begin = (TextView) findViewById(R.id.txtRiderName_StartTrip);
        btnStartTrip = (Button) findViewById(R.id.btnStartTrip);
        endTripLayout = (RelativeLayout) findViewById(R.id.end_trip__layout);
        txtEndTrip = (TextView) findViewById(R.id.txtRiderName_EndTrip);
        btnEndTrip = (Button) findViewById(R.id.btnEndTrip);
        riderinfo = (ImageButton) findViewById(R.id.riderinfo);
        riderinfoinarrived=(ImageButton) findViewById(R.id.riderinfoinarrived);

        SharedPreferences prefs = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
        driverId = prefs.getString("driverid", null);
        drivername = prefs.getString("drivername", null);
        strCategory= prefs.getString("carcategory", null);
        System.out.println("driverid In MAPP" + driverId);
        System.out.println("driverNAMEand CAR In MAPP" + drivername + strCategory);
        checkphonenumber();
        bottomBar = (BottomBar) findViewById(R.id.bottomBar);
        getOnlineStatus();


        // setup GeoFire with category
        if(strCategory!=null && !strCategory.isEmpty()){
            geoFire = new GeoFire(FirebaseDatabase.getInstance().getReference().child("drivers_location").child(strCategory));
        }
        else{
            geoFire = new GeoFire(FirebaseDatabase.getInstance().getReference().child("drivers_location"));
        }

        getCarCategoryrDetails();

        System.out.println("Category user "+strCategory);

        mGoogleApiClient = new GoogleApiClient.Builder(this)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build();

        //Easy location to get instant updates
        LocationRequest locationRequest = new LocationRequest()
                .setPriority(LocationRequest.PRIORITY_BALANCED_POWER_ACCURACY)
                .setInterval(1000)
                .setFastestInterval(1000);
        easyLocationRequest = new EasyLocationRequestBuilder()
                .setLocationRequest(locationRequest)
                .setLocationPermissionDialogTitle(getString(R.string.location_permission_dialog_title))
                .setLocationPermissionDialogMessage(getString(R.string.location_permission_dialog_message))
                .setLocationPermissionDialogNegativeButtonText(getString(R.string.not_now))
                .setLocationPermissionDialogPositiveButtonText(getString(R.string.yes))
                .setLocationSettingsDialogTitle(getString(R.string.location_services_off))
                .setLocationSettingsDialogMessage(getString(R.string.open_location_settings))
                .setLocationSettingsDialogNegativeButtonText(getString(R.string.not_now))
                .setLocationSettingsDialogPositiveButtonText(getString(R.string.yes))
                .setFallBackToLastLocationTime(1000)
                .build();
        requestLocationUpdates(easyLocationRequest);

        btnArriveNow.setOnClickListener(new View.OnClickListener()

        {

            @Override
         public void onClick(View v) {
                getState.putString("tripstate", "arriveClicked");
                getState.commit();

                arriveNowLayout.setVisibility(View.GONE);
                startTripLayout.setVisibility(View.VISIBLE);
                destinationLayout.setVisibility(View.VISIBLE);

                DatabaseReference databaseReference = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
                Map<String, Object> taskMap = new HashMap<String, Object>();
                taskMap.put("status", "2");
                databaseReference.updateChildren(taskMap);
                updateArriveRequest();

            }
        });

        bottomBar.setOnTabSelectListener(new OnTabSelectListener() {
            @Override
            public void onTabSelected(@IdRes int tabId) {

                if (tabId == R.id.tab_profile) {

                    Intent intent = new Intent(Map_Activity.this, SettingActivity_.class);
                    startActivity(intent);
                }

                if (tabId == R.id.tab_rating) {

                    Intent intent = new Intent(Map_Activity.this, RatingActivity_.class);
                    startActivity(intent);
                }

                if (tabId == R.id.tab_earning) {

                    Intent intent = new Intent(Map_Activity.this, EarningActivity_.class);
                    startActivity(intent);
                }


            }
        });

        FAB = (RelativeLayout) findViewById(R.id.myLocationButton);
        FAB.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mCurrentLocation != null) {
                    LatLng latLng = new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

                    System.out.println("INSIDE LOCAION CHANGE" + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
                    CameraPosition cameraPosition = new CameraPosition.Builder()
                            .target(latLng)                              // Sets the center of the map to current location
                            .zoom(15)
                            .tilt(0)                                     // Sets the tilt of the camera to 0 degrees
                            .build();
                    mMap.animateCamera(CameraUpdateFactory.newCameraPosition(cameraPosition));

                }

            }
        });


        btnStartTrip.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                getState.putString("tripstate", "startClicked");
                getState.commit();
                arriveNowLayout.setVisibility(View.GONE);
                endTripLayout.setVisibility(View.VISIBLE);
                destinationLayout.setVisibility(View.VISIBLE);
                DatabaseReference databaseReference = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
                Map<String, Object> taskMap = new HashMap<String, Object>();
                taskMap.put("status", "3");
                databaseReference.updateChildren(taskMap);
                startUpdateTrip();
                strDistanceBegin = "distancebegin";
                distance = 0;
                //removeTripListener();
            }
        });

        toll_pay.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                tollFee();
            }
        });

        btnEndTrip.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                mMap.clear();
                getState.putString("tripstate", "endClicked");
                getState.commit();

                previousToll=0;

                tripState = "btnendClicked";
                strDistanceBegin = "totaldistancend";
                tripStatus = "end";
                //gettripID();

                destLocation = null;

                if (mCurrentLocation != null) {
                    LatLng latLng = new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

                    System.out.println("INSIDE ENDTRIP CHANGE" + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
                    CameraPosition cameraPosition = new CameraPosition.Builder()
                            .target(latLng)                              // Sets the center of the map to current location
                            .zoom(15)
                            .tilt(0)                                     // Sets the tilt of the camera to 0 degrees
                            .build();
                    mMap.moveCamera(CameraUpdateFactory.newCameraPosition(cameraPosition));
                    mMap.addMarker(new MarkerOptions()
                            .icon(BitmapDescriptorFactory.fromResource(R.mipmap.car))
                            .position(latLng));
                }


                endTripLayout.setVisibility(View.GONE);
                bottomBar.setVisibility(View.VISIBLE);
                toolbarLayout.setVisibility(View.VISIBLE);
                startTripLayout.setVisibility(View.GONE);
                destinationLayout.setVisibility(View.GONE);
                //Add fee to firebase to notify rider
                if(driverId!=null&&!driverId.isEmpty()){
                    DatabaseReference databaseReference2 = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
                    Map<String, Object> taskMap2 = new HashMap<String, Object>();
                    taskMap2.put("tollfee","0");
                    databaseReference2.updateChildren(taskMap2);
                }

                DatabaseReference databaseReference = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
                Map<String, Object> taskMap = new HashMap<String, Object>();
                taskMap.put("status", "4");
                databaseReference.updateChildren(taskMap);
                showTripSummaryDialog();
                endUpdateTrip();


            }
        });




        //riderInfo
        riderinfo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                animY.cancel();
                showRiderInfoDialog();
            }
        });

        riderinfoinarrived.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showRiderInfoDialog();
            }
        });

        client = new GoogleApiClient.Builder(this).addApi(AppIndex.API).build();

        if (tripState.matches("requestAccept")) {
            getAcceptState();
        } else if (tripState.matches("arriveClicked")) {
            tripID = state.getString("tripID", null);
            getStartState();
        } else if (tripState.matches("startClicked"))

        {
            tripID = state.getString("tripID", null);
            getendState();
        }
    }


    public void tollFee(){
        dialog = new Dialog(Map_Activity.this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.call_rider_dialog);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        dialog.getWindow().getAttributes().windowAnimations = R.style.DialogAnimation;
        ImageButton back = (ImageButton) dialog.findViewById(R.id.tollclose);
        Button addToll= (Button) dialog.findViewById(R.id.addtoll);
        final EditText fee_edit_text=(EditText)dialog.findViewById(R.id.fee_edit_text);

        CardView infoCard=(CardView)dialog.findViewById(R.id.card_view2_rider_info);
        infoCard.setVisibility(View.GONE);

        CardView tollFeeCard=(CardView)dialog.findViewById(R.id.card_view2_toll_fee);
        tollFeeCard.setVisibility(View.VISIBLE);



        back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.cancel();
            }
        });

        addToll.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try{
                    int strTotal=Integer.valueOf(fee_edit_text.getText().toString());

                    strTotal=strTotal+previousToll;
                    previousToll=strTotal;

                    getState.putInt("tollfee",previousToll);
                    getState.commit();

                    //Add fee to firebase to notify rider
                    if(driverId!=null&&!driverId.isEmpty()){
                        DatabaseReference databaseReference2 = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
                        Map<String, Object> taskMap2 = new HashMap<String, Object>();
                        taskMap2.put("tollfee", String.valueOf(previousToll));
                        databaseReference2.updateChildren(taskMap2);
                    }

                    setToll(String.valueOf(previousToll));
                    Toast.makeText(Map_Activity.this, "Toll Added", Toast.LENGTH_SHORT).show();

                    dialog.cancel();
                }catch (Exception e){
                    e.printStackTrace();
                }

            }
        });

        dialog.show();
    }

    public void setToll(String toll){
        this.tollfee=toll;

    }

    public String getToll(){
        if(tollfee!=null){
            return tollfee;
        }
        else{
            return "0";
        }

    }

    public void setTotalPrice(String price){
        this.totalprice=price;

    }

    public String getTotalPrice(){
        if(totalprice!=null){
            return totalprice;
        }
        else{
            return "0";
        }

    }

    private void getCarCategoryrDetails()
    {
        String url = Constants.CATEGORY_LIVE_URL + "Settings/getCategory";
        System.out.println(" CATEGOR URL is " + url);

        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {
                                JSONObject signIn_jsonobj = response.getJSONObject(i);
                                strWebCategory = signIn_jsonobj.getString("categoryname");
                                System.out.println("Current category"+strCategory);
                                System.out.println("Web category"+strWebCategory);

                                if(strCategory.equals(strWebCategory))
                                {
                                    strWebprice_km=signIn_jsonobj.optString("price_km");
                                    strwebpricepermin=signIn_jsonobj.optString("price_minute");
                                    strwebmaxsize=signIn_jsonobj.optString("max_size");
                                    strwebpricefare=signIn_jsonobj.optString("price_fare");

                                    System.out.println("Price per KM "+strWebprice_km);
                                    System.out.println("Price Per Min"+strwebpricepermin);
                                    System.out.println("Max Size "+strwebmaxsize);
                                    System.out.println("Price Fare "+strwebpricefare);




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

    private void showRiderInfoDialog() {
        dialog = new Dialog(Map_Activity.this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.call_rider_dialog);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));

        TextView ridername= (TextView) dialog.findViewById(R.id.ridername);
        ImageView riderimg= (ImageView) dialog.findViewById(R.id.rider_image);
        ImageButton back = (ImageButton) dialog.findViewById(R.id.backButton);
        LinearLayout call = (LinearLayout) dialog.findViewById(R.id.calllayout);
        TextView txtCancelTrip=(TextView ) dialog.findViewById(R.id.txtCancelTrip);
        TextView cartype=(TextView ) dialog.findViewById(R.id.car_type);
        LinearLayout msg = (LinearLayout) dialog.findViewById(R.id.msglayout);


        Window window = dialog.getWindow();
        WindowManager.LayoutParams wlp = window.getAttributes();
        wlp.width= ViewGroup.LayoutParams.MATCH_PARENT;
        wlp.gravity = Gravity.TOP;
        window.setAttributes(wlp);

        if(strCategory!=null&&!strCategory.isEmpty()) {
            cartype.setText(strCategory);
        }
        back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.cancel();
            }
        });

        if(riderFirstName!=null&&!riderFirstName.isEmpty()){
            ridername.setText(riderFirstName+" "+riderLastName);
        }

        // cancel onclick
        txtCancelTrip.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                android.support.v7.app.AlertDialog.Builder builder =
                        new android.support.v7.app.AlertDialog.Builder(Map_Activity.this, R.style.AppCompatAlertDialogStyle);
                builder.setTitle(getString(R.string.cancel_trip));
                builder.setMessage(getString(R.string.cancel_dis));
                builder.setCancelable(false);
                builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog1, int which) {

                        DatabaseReference databaseReference2 = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
                        Map<String, Object> taskMap2 = new HashMap<String, Object>();
                        taskMap2.put("status", "5");
                        databaseReference2.updateChildren(taskMap2);

                        strCacnelStatus="drivercliked";
                        clearFirebaseData();
                        getCancelState();
                        cancelTrip();
                        dialog1.cancel();
                        dialog.cancel();


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
        });

        if(strRiderProfile!=null && !strRiderProfile.isEmpty()){
            Glide.with(Map_Activity.this)
                    .load(strRiderProfile)
                    .skipMemoryCache(true)
                    .transform(new RoundImageTransform(Map_Activity.this))
                    .into(new GlideDrawableImageViewTarget(riderimg) {
                        @Override
                        public void onResourceReady(GlideDrawable drawable, GlideAnimation anim) {
                            super.onResourceReady(drawable, anim);
                        }
                    });
        }


        msg.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (ridermobile != null && !ridermobile.isEmpty()) {
                    Intent sendIntent = new Intent(Intent.ACTION_VIEW);
                    sendIntent.setData(Uri.parse("sms:"+ridermobile));
                    startActivity(sendIntent);
                } else {
                    Toast.makeText(Map_Activity.this, "Number not register", Toast.LENGTH_SHORT).show();
                }

            }
        });

        call.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (ridermobile != null && !ridermobile.isEmpty()) {
                    Intent intent = new Intent(Intent.ACTION_CALL,Uri.parse("tel:"+ridermobile));

                    if (ActivityCompat.checkSelfPermission(Map_Activity.this, Manifest.permission.CALL_PHONE) != PackageManager.PERMISSION_GRANTED) {
                        // TODO: Consider calling
                        //    ActivityCompat#requestPermissions
                        // here to request the missing permissions, and then overriding
                        //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                        //                                          int[] grantResults)
                        // to handle the case where the user grants the permission. See the documentation
                        // for ActivityCompat#requestPermissions for more details.
                        return;
                    }
                    try {
                        startActivity(intent);
                    } catch (android.content.ActivityNotFoundException ex) {
                        Toast.makeText(getApplicationContext(), "Number is not founded", Toast.LENGTH_SHORT).show();
                    }
                }
                else{
                    Toast.makeText(Map_Activity.this, "Number not register", Toast.LENGTH_SHORT).show();
                }
            }
        });

        dialog.show();
    }

    private void showCancelDialog() {

        android.support.v7.app.AlertDialog.Builder builder =
                new android.support.v7.app.AlertDialog.Builder(Map_Activity.this, R.style.AppCompatAlertDialogStyle);
        builder.setTitle(getString(R.string.cancel_trip));
        builder.setMessage(getString(R.string.rider_cancel));
        builder.setCancelable(false);
        builder.setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog1, int which) {

                Intent intent = new Intent(Map_Activity.this, Map_Activity.class);
                startActivity(intent);

            }

        });


        builder.show();

    }

    private void cancelTrip()
    {
        String url = Constants.LIVEURL_REQUEST + "updateTrips/trip_id/" + tripID + "/trip_status/cancel/accept_status/5/distance/0/total_amount/0";
        System.out.println(" ONLINE URL is " + url);
        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try
                            {
                                System.out.println("Cancelled the trip!!");
                            }
                            catch (Exception e)
                            {//stopAnim();
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
                    // Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));
    }

    private void checkphonenumber() {
        String url = Constants.LIVEURL+ "editProfile/user_id/"+driverId;
        System.out.println(" Checkphone URL is " + url);

        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {

                                JSONObject signIn_jsonobj = response.getJSONObject(i);
                                String mobile = signIn_jsonobj.getString("mobile");
                                if(mobile.isEmpty() && mobile.matches("")){
                                    showAlert();
                                }

                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    // Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));
    }

    private void showAlert() {
        dialog = new Dialog(Map_Activity.this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.set_phone_number_dialog);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        dialog.getWindow().getAttributes().windowAnimations = R.style.DialogAnimation;
        dialog.setCancelable(false);
        // set the custom dialog components - text, image and button
        final Typeface face= Typeface.createFromAsset(getApplicationContext().getAssets(), getString(R.string.app_font));

        TextView setphone= (TextView) dialog.findViewById(R.id.setphone);
        setphone.setTypeface(face);
        ImageButton setlater= (ImageButton) dialog.findViewById(R.id.setcancel);

        TextView needphntxt= (TextView) dialog.findViewById(R.id.needphnnotxt);
        needphntxt.setTypeface(face);
        TextView needphntxttitle= (TextView) dialog.findViewById(R.id.needphnnotxttitle);
        needphntxttitle.setTypeface(face);

        setphone.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i=new Intent(Map_Activity.this,SettingActivity_.class);
                startActivity(i);
            }
        });
        setlater.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.cancel();
            }
        });

        dialog.show();
    }

    private void createDistanceFireBase() {

        if(driverId!=null){
            tripID=state.getString("tripID",null);
            System.out.println("Inside the save  distance===>"+tripID);

            ratingRef = FirebaseDatabase.getInstance().getReference().child("trips_data").child(tripID);
            Map<String, Object> updates = new HashMap<String, Object>();
            updates.put("Distance","0");
            updates.put("Price","0");
            updates.put("rider_rating","0");
            updates.put("driver_rating","0");
            System.out.println("Total price"+strTotalPrice);

            ratingRef.setValue(updates, new DatabaseReference.CompletionListener() {
                @Override
                public void onComplete(DatabaseError databaseError, DatabaseReference databaseReference) {
                    System.out.println("DATA SAVED SUCCESSFULLY");
                    if (databaseError != null) {
                        System.out.println("DATA SAVED SUCCESSFULLY");
                    }
                }
            });
        }

    }

    public void getCancelState(){

        mMap.clear();
        getState.putString("tripstate", "endClicked");
        getState.commit();

        tripState = "btnendClicked";
        strDistanceBegin = "totaldistancend";
        tripStatus = "end";
        //gettripID();

        destLocation = null;

        if (mCurrentLocation != null) {
            LatLng latLng = new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

            System.out.println("INSIDE ENDTRIP CHANGE" + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
            CameraPosition cameraPosition = new CameraPosition.Builder()
                    .target(latLng)                              // Sets the center of the map to current location
                    .zoom(15)
                    .tilt(0)                                     // Sets the tilt of the camera to 0 degrees
                    .build();
            mMap.moveCamera(CameraUpdateFactory.newCameraPosition(cameraPosition));
            mMap.addMarker(new MarkerOptions()
                    .icon(BitmapDescriptorFactory.fromResource(R.mipmap.car))
                    .position(latLng));
        }


        endTripLayout.setVisibility(View.GONE);
        bottomBar.setVisibility(View.VISIBLE);
        toolbarLayout.setVisibility(View.VISIBLE);
        startTripLayout.setVisibility(View.GONE);
        destinationLayout.setVisibility(View.GONE);
    }

    private void updateDistanceFireBase() {

        if(driverId!=null){
            tripID=state.getString("tripID",null);
            System.out.println("Inside the save  distance===>"+tripID);

            DatabaseReference ref = FirebaseDatabase.getInstance().getReference().child("trips_data").child(tripID);
            Map<String, Object> updates = new HashMap<String, Object>();
            updates.put("Distance",strTotalDistance);
            updates.put("Price",strTotalPrice);
            System.out.println("Total price"+strTotalPrice);

            ratingRef.updateChildren(updates);
        }
    }

    private void updateDriverRating(String driverRating)
    {
        if(driverId!=null){
            tripID=state.getString("tripID",null);
            System.out.println("Inside the save  distance===>"+tripID);

            DatabaseReference ref = FirebaseDatabase.getInstance().getReference().child("trips_data").child(tripID);
            Map<String, Object> updates = new HashMap<String, Object>();
            updates.put("driver_rating",driverRating);

            System.out.println("Total rating"+driverRating);

            ratingRef.updateChildren(updates);
        }

    }


    public void getRating() {
        tripID=state.getString("tripID",null);
        flexibleRatingBar=(FlexibleRatingBar)dialogTripSummary.findViewById(R.id.flexibleRatingBar);
        ratingReference = FirebaseDatabase.getInstance().getReference().child("trips_data").child(tripID+"/rider_rating");
        ratingListener = ratingReference.addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                if(dataSnapshot.getValue()!=null) {
                    ratingInt = dataSnapshot.getValue().toString();
                    if(ratingInt!=null && !ratingInt.isEmpty()){
                        try{
                            System.out.println("DATASNAPSHOTTT Rattinggggg" + ratingInt);
                            Float rating=Float.parseFloat(ratingInt);
                            flexibleRatingBar.setRating(rating);
                        }catch (Exception e){
                            e.printStackTrace();
                        }

                    }
                }
            }
            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });
    }

    private void endUpdateTrip()

    {
        String url = Constants.LIVEURL_REQUEST + "updateTrips/trip_id/" + tripID + "/trip_status/end/accept_status/4/distance/"+strTotalDistance+"/total_amount/"+getTotalPrice();
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
                                riderID = signIn_jsonobj.getString("rider_id");
                                String strDestination = signIn_jsonobj.getString("destination");
                                JSONObject jsonArray = new JSONObject(strDestination);
                                strLat = jsonArray.getString("lat");
                                strLng = jsonArray.getString("long");

                                double double_lat = Double.parseDouble(strLat);
                                double double_lng = Double.parseDouble(strLng);

                                System.out.println("fffffff" + riderID);
                                System.out.println("latitiudee OF DESTINATION" + strLat);
                                System.out.println("longitude OF DESTINATION" + strLng);


                                //getCompleteAddressString(double_lat, double_lng);
                                getRiderDetails();


                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    // Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));
    }


    private void startUpdateTrip() {
        String url = Constants.LIVEURL_REQUEST + "updateTrips/trip_id/" + tripID + "/trip_status/on/accept_status/3/total_amount/0";
        System.out.println(" ONLINE URL is " + url);

        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {
                                //      Toast.makeText(Map_Activity.this, "4", Toast.LENGTH_SHORT).show();
                                JSONObject signIn_jsonobj = response.getJSONObject(i);
                                riderID = signIn_jsonobj.getString("rider_id");
                                String strDestination = signIn_jsonobj.getString("destination");
                                JSONObject jsonArray = new JSONObject(strDestination);
                                strLat = jsonArray.getString("lat");
                                strLng = jsonArray.getString("long");

                                double double_lat = Double.parseDouble(strLat);
                                double double_lng = Double.parseDouble(strLng);

                                System.out.println("fffffff" + riderID);
                                System.out.println("latitiudee OF DESTINATION" + strLat);
                                System.out.println("longitude OF DESTINATION" + strLng);
                                strsetValue = "coming_start";
                                destLocation = new LatLng(double_lat, double_lng);
                                if(mCurrentLocation!=null)
                                orginLocation=new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

                                //polyline:
                                GoogleDirection.withServerKey(getString(R.string.direction_api_key))
                                        .from(orginLocation)
                                        .to(destLocation)
                                        .transportMode(TransportMode.DRIVING)
                                        .execute(Map_Activity.this);

                              /*  //adjust latlng bounds
                                try{
                                    Location temp = new Location(LocationManager.GPS_PROVIDER);
                                    temp.setLatitude(double_lat);
                                    temp.setLongitude(double_lng);

                                    markers.add(mCurrentLocation);
                                    markers.add(temp);

                                    LatLngBounds.Builder b = new LatLngBounds.Builder();
                                    for (Location m : markers) {
                                        LatLng latlng=new LatLng(m.getLatitude(),m.getLongitude());
                                        b.include(latlng);
                                    }
                                    LatLngBounds bounds = b.build();
                                    //Change the padding as per needed
                                    CameraUpdate cu = CameraUpdateFactory.newLatLngBounds(bounds,15);
                                    mMap.moveCamera(cu);
                                }catch (Exception e){
                                    System.out.println("THe Exception in bounds");
                                }

*/
                                getCompleteAddressString(double_lat, double_lng);
                                getRiderDetails();


                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            } catch (NullPointerException e){
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    //   Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

    }


    private void arriveNowFunction() {
        gettripID();
        arriveNowLayout.setVisibility(View.VISIBLE);
        //showDialog("0");
        animY = ObjectAnimator.ofFloat(riderinfo, "translationY", -100f, 0f);
        animY.setDuration(2000);//1sec
        animY.setInterpolator(new BounceInterpolator());
        animY.setRepeatCount(1);
        animY.start();

        toolbarLayout.setVisibility(View.GONE);
        bottomBar.setVisibility(View.GONE);
        destinationLayout.setVisibility(View.VISIBLE);
    }

    private void updateArriveRequest()
    {

        //http://demo.cogzidel.com/arcane_lite/requests/updateTrips/trip_id/5858fd6b49ad6a3708b7acd9/trip_status/on/accept_status/2
        String url = Constants.LIVEURL_REQUEST + "updateTrips/trip_id/" + tripID + "/trip_status/off/accept_status/2/total_amount/0";
        System.out.println(" ONLINE URL is " + url);
        // Creating volley request obj
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response)
                    {
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {

                                JSONObject signIn_jsonobj = response.getJSONObject(i);
                                riderID = signIn_jsonobj.getString("rider_id");
                                String strDestination = signIn_jsonobj.getString("destination");
                                JSONObject jsonArray = new JSONObject(strDestination);
                                strLat = jsonArray.getString("lat");
                                strLng = jsonArray.getString("long");

                                double double_lat = Double.parseDouble(strLat);
                                double double_lng = Double.parseDouble(strLng);

                                System.out.println("fffffff" + riderID);
                                System.out.println("latitiudee" + strLat);
                                System.out.println("longitude" + strLng);

                                strsetValue = "coming_arrive";

                                getRiderDetails();

                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();

                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

    }

    private void upDateRequest(Double lat, Double lng)

    {
        String url = Constants.LIVEURL_REQUEST + "updateRequest/request_id/" + reqID + "/driver_id/" + driverId + "/request_status/accept/" + "lat/" + lat + "/long/" + lng;
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
                                riderID = signIn_jsonobj.getString("rider_id");
                                String strPickup = signIn_jsonobj.getString("pickup");
                                JSONObject jsonArray = new JSONObject(strPickup);
                                strLat = jsonArray.getString("lat");
                                strLng = jsonArray.getString("long");


                                double double_lat = Double.parseDouble(strLat);
                                double double_lng = Double.parseDouble(strLng);

                                System.out.println("fffffff" + riderID);
                                System.out.println("latitiudee" + strLat);
                                System.out.println("longitude" + strLng);


                                destLocation = new LatLng(double_lat, double_lng);

                                if(mCurrentLocation!=null)
                                orginLocation=new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

                                strsetValue = "updatereq";
                                strsetdestination = "updatereq";

                                //polyline:
                                GoogleDirection.withServerKey(getString(R.string.direction_api_key))
                                        .from(orginLocation)
                                        .to(destLocation)
                                        .transportMode(TransportMode.DRIVING)
                                        .execute(Map_Activity.this);

                                getCompleteAddressString(double_lat, double_lng);
                                getRiderDetails();


                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    //    Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));
    }


    private void getRiderDetails() {
        final String url = Constants.LIVEURL_RIDER + "editProfile/user_id/" + riderID;
        System.out.println("RiderProfileURL==>" + url);
        final JsonArrayRequest signUpReq = new JsonArrayRequest(url, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                for (int i = 0; i < response.length(); i++) {
                    try {
                        JSONObject jsonObject = response.getJSONObject(i);
                        status = jsonObject.optString("status");
                        message = jsonObject.optString("message");


                        if (status.equals("Success")) {
                            riderFirstName = jsonObject.optString("firstname");

                            riderLastName = jsonObject.optString("lastname");
                            strRiderProfile = jsonObject.optString("profile_pic");
                            ridermobile = jsonObject.optString("mobile");
                            riderFirstName=riderFirstName.replaceAll("%20"," ");
                            riderLastName=riderLastName.replaceAll("%20"," ");

                            if(riderLastName.matches("null"))
                                riderLastName=" ";



                            try {
                                if (strsetValue == "updatereq") {

                                    txtRiderName.setText(riderFirstName + " " + riderLastName);
                                } else if (strsetValue == "coming_arrive") {

                                    txtRiderName_Begin.setText(riderFirstName + " " + riderLastName);
                                } else if (strsetValue == "coming_end") ;
                                {
                                    txtEndTrip.setText(riderFirstName + " " + riderLastName);



                                }



                            } catch (NullPointerException e) {
                                e.printStackTrace();
                            }
                            //     dismissDialog();
                        } else {
//                            Toast.makeText(getApplicationContext(), message,Toast.LENGTH_SHORT).show();
                            // dismissDialog();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                    } catch (NullPointerException e) {
                        e.printStackTrace();
                    }
                    //  dismissDialog();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError volleyError) {
                if (volleyError instanceof NoConnectionError) {
                    //   dismissDialog();
                    Toast.makeText(getApplicationContext(), "No internet connection", Toast.LENGTH_SHORT).show();
                }
            }
        });

        AppController.getInstance().addToRequestQueue(signUpReq);
        signUpReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 0, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
    }


    public void getOnlineStatus() {
        SharedPreferences prefs = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
        onlinecheck = prefs.getString("onlinestatus", null);
        if (onlinecheck != null && !onlinecheck.isEmpty()) {
            if (onlinecheck.matches("online")) {
                clicked = true;
                onlineTxt.setText(R.string.go_offline);
                getRequestStatus();
            } else {
                clicked = false;
                onlineTxt.setText(R.string.go_online);
            }
        }
    }


    /**
     * Manipulates the map once available.
     * This callback is triggered when the map is ready to be used.
     * This is where we can add markers or lines, add listeners or move the camera. In this case,
     * we just add a marker near Sydney, Australia.
     * If Google Play services is not installed on the device, the user will be prompted to install
     * it inside the SupportMapFragment. This method will only be triggered once the user has
     * installed Google Play services and returned to the app.
     */
    @Override
    public void onMapReady(GoogleMap googleMap)
    {
        mMap = googleMap;
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return;
        }
        //mMap.setMyLocationEnabled(true);
        mMap.getMinZoomLevel();

        //Map Camera Move listener
        mMap.setOnCameraMoveListener(new GoogleMap.OnCameraMoveListener() {
            @Override
            public void onCameraMove() {
                FAB.setVisibility(View.GONE);
                toolbarLayout.setVisibility(View.GONE);
                bottomBar.setVisibility(View.GONE);
            }
        });
        mMap.setOnCameraMoveStartedListener(new GoogleMap.OnCameraMoveStartedListener() {
            @Override
            public void onCameraMoveStarted(int i) {
                FAB.setVisibility(View.GONE);
                toolbarLayout.setVisibility(View.GONE);
                bottomBar.setVisibility(View.GONE);
            }
        });
        //Map Camera Idle listener
        mMap.setOnCameraIdleListener(new GoogleMap.OnCameraIdleListener() {
            @Override
            public void onCameraIdle() {
               if(tripState!=null&&!tripState.isEmpty()){
                   if(tripState.matches("newRequest")||tripState.matches("requestAccept")||tripState.matches("arriveClicked")||tripState.matches("startClicked")){
                       toolbarLayout.setVisibility(View.GONE);
                       bottomBar.setVisibility(View.GONE);
                   }
                   else{
                       toolbarLayout.setVisibility(View.VISIBLE);
                       bottomBar.setVisibility(View.VISIBLE);
                   }

               }
                FAB.setVisibility(View.VISIBLE);
            }
        });

        if (mapView != null &&
                mapView.findViewById(Integer.parseInt("1")) != null) {
            // Get the button view
            View locationButton = ((View) mapView.findViewById(Integer.parseInt("1")).getParent()).findViewById(Integer.parseInt("2"));
            // and next place it, on bottom right (as Google Maps app)
            RelativeLayout.LayoutParams layoutParams = (RelativeLayout.LayoutParams)
                    locationButton.getLayoutParams();
            // position on right bottom
            layoutParams.addRule(RelativeLayout.ALIGN_PARENT_TOP, RelativeLayout.TRUE);
            layoutParams.addRule(RelativeLayout.ALIGN_PARENT_BOTTOM, 0);
            layoutParams.setMargins(30, 180, 0, 0);
        }
        try{
            mMap.setOnMapLoadedCallback(new GoogleMap.OnMapLoadedCallback() {
                @Override
                public void onMapLoaded() {
                    // Make a snapshot when map's done loading
                    mMap.snapshot(new GoogleMap.SnapshotReadyCallback() {
                        @Override
                        public void onSnapshotReady(Bitmap bitmap) {
                            //Getting Map as Bitmap
                            mapBitmap = bitmap;

                            // If map won't be used afterwards, remove it's views
//              ((FrameLayout)findViewById(R.id.map)).removeAllViews();
                        }
                    });
                }
            });
        }catch (Exception e){
            System.out.println("Exception"+e);
        }

        d = new Dialog(Map_Activity.this, android.R.style.Theme_Black_NoTitleBar_Fullscreen);
        d.setContentView(R.layout.activity_progress_bar);

        pwOne = (ProgressWheel) d.findViewById(R.id.progressBarTwo);
        progresslayout = (RelativeLayout) d.findViewById(R.id.progresslayout);
        requestMapView = (ImageView) d.findViewById(R.id.map_view);
        pwOne.setBarWidth(10);
        pwOne.setRimWidth(10);
        pwOne.setRimColor(Color.WHITE);



        MarkerPoints = new ArrayList<>();

        dialogTripSummary = new Dialog(Map_Activity.this, android.R.style.Theme_DeviceDefault_Light_NoActionBar_Fullscreen);
        dialogTripSummary.setContentView(R.layout.activity_trip_summary);

        try {

            // Customise the styling of the base map using a JSON object defined
            // in a raw resource file.
            boolean success = mMap.setMapStyle(
                    MapStyleOptions.loadRawResourceStyle(
                            this, R.raw.style));

            if (!success) {
                Log.e("Map_Activity", "Style parsing failed.");
            }
        } catch (Resources.NotFoundException e) {
            Log.e("Map_Activity", "Can't find style. Error: ", e);
        }


        checkPermission();
        System.out.println("INSIDE MAPREADY");

        mCurrentLocation=getLastKnownLocation();
        if(mCurrentLocation!=null){
            LatLng latLng = new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

            System.out.println("INSIDE LOCAION CHANGE" + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
            CameraPosition cameraPosition = new CameraPosition.Builder()
                    .target(latLng)                              // Sets the center of the map to current location
                    .zoom(15)
                    .tilt(0)                                     // Sets the tilt of the camera to 0 degrees
                    .build();
            mMap.moveCamera(CameraUpdateFactory.newCameraPosition(cameraPosition));
            mMap.addMarker(new MarkerOptions()
                    .icon(BitmapDescriptorFactory.fromResource(R.mipmap.car))
                    .position(latLng));
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, String permissions[], int[] grantResults) {
        switch (requestCode) {
            case MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION: {
                // If request is cancelled, the result arrays are empty.
                if (grantResults.length > 0
                        && grantResults[0] == PackageManager.PERMISSION_GRANTED)
                {
                    // permission was granted, yay! Do the task you need to do.
                    startLocationUpdates();
                }
                else
                {
                    // permission denied, boo! Disable the functionality that depends on this permission.
                }
                return;
            }
        }
    }

    public void clickOnline(View v)
    {
        if (!clicked) {
            getProofStatus();

        } else {
            clicked = false;
            onlinestatus = "0";
            editor.putString("onlinestatus", "offline");
            editor.commit();
            setonline(onlinestatus);
            onlineTxt.setText(R.string.go_online);
            TSnackbar snackbar = TSnackbar.make(findViewById(android.R.id.content), getResources().getString(R.string.you_are_now_offline), TSnackbar.LENGTH_LONG);
            snackbar.setActionTextColor(Color.RED);
            View snackbarView = snackbar.getView();
            snackbarView.setBackgroundColor(Color.RED);
            TextView textView = (TextView) snackbarView.findViewById(com.androidadvance.topsnackbar.R.id.snackbar_text);
            textView.setTextColor(Color.WHITE);
            textView.setTypeface(null, Typeface.BOLD);
            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.WRAP_CONTENT, RelativeLayout.LayoutParams.WRAP_CONTENT));
            params.setMargins(0,30,0,0);
            textView.setLayoutParams(params);
            snackbar.show();
            if(mCurrentLocation!=null)
                onLocationReceived(mCurrentLocation);
        }
    }
    public void getProofStatus(){
        proofstatusref = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("proof_status");
        proofstatusref.addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                if (dataSnapshot.getValue() != null) {
                    String proofstatus = dataSnapshot.getValue().toString();
                    if (!proofstatus.isEmpty() && proofstatus.length() != 0) {
                        if (proofstatus.matches("Accepted")) {
                            online_method();
                        } else {
                            showdialog();
                            //gotooffline();
                        }
                    }
                }
            }

            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });
     /*   String url = Constants.LIVEURL + "checkProofStatus/userid/" + driverId ;
        System.out.println(" Proofstatus URL is " + url);

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
                                String proof_status = signIn_jsonobj.getString("proof_status");

                                if (signIn_status.equals("Success"))
                                {
                                    if(proof_status!=null){
                                        if(proof_status.matches("Accepted")){
                                            online_method();
                                        }

                                    }

                                } else if(signIn_status.equals("Fail")){
                                    showdialog();
                                }

                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    //
                    //    Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));*/

    }

    private void gotooffline() {
        clicked = false;
        onlinestatus = "0";
        editor.putString("onlinestatus", "offline");
        editor.commit();
        setonline(onlinestatus);
        onlineTxt.setText(R.string.go_online);
    }

    public void online_method(){
        clicked = true;
        onlinestatus = "1";
        editor.putString("onlinestatus", "online");
        editor.commit();
        setonline(onlinestatus);
        onlineTxt.setText(R.string.go_offline);
        TSnackbar snackbar = TSnackbar.make(findViewById(android.R.id.content), getResources().getString(R.string.you_are_now_online), TSnackbar.LENGTH_LONG);
        snackbar.setActionTextColor(Color.GREEN);
        View snackbarView = snackbar.getView();
        snackbarView.setBackgroundColor(getResources().getColor(android.R.color.holo_green_light));
        TextView textView = (TextView) snackbarView.findViewById(com.androidadvance.topsnackbar.R.id.snackbar_text);
        textView.setTextColor(Color.WHITE);
        textView.setTypeface(null, Typeface.BOLD);
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.WRAP_CONTENT, RelativeLayout.LayoutParams.WRAP_CONTENT));
        params.setMargins(0,30,0,0);
        textView.setLayoutParams(params);
        snackbar.show();
        if(mCurrentLocation!=null)
            onLocationReceived(mCurrentLocation);
        getRequestStatus();
    }

    public void showdialog(){
        final TSnackbar snackbar = TSnackbar.make(findViewById(android.R.id.content), getResources().getString(R.string.proof_not_accept), TSnackbar.LENGTH_LONG);
        snackbar.setActionTextColor(Color.RED);
        View snackbarView = snackbar.getView();
        snackbarView.setBackgroundColor(Color.RED);
        TextView textView = (TextView) snackbarView.findViewById(com.androidadvance.topsnackbar.R.id.snackbar_text);
        textView.setTextColor(Color.WHITE);
        textView.setTypeface(null, Typeface.BOLD);
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.WRAP_CONTENT, RelativeLayout.LayoutParams.WRAP_CONTENT));
        params.setMargins(0, 30, 0, 0);
        textView.setLayoutParams(params);
        snackbar.show();
    }
    public void setonline(String online) {

        onlineLay.setEnabled(false);

        String url = Constants.LIVEURL + "updateOnlineStatus/userid/" + driverId + "/online_status/" + online;
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
                                String signIn_status = signIn_jsonobj.getString("status");

                                if (signIn_status.equals("Success"))
                                {
                                    onlineLay.setEnabled(true);

                                } else if (signIn_status.equals("Fail"))
                                {
                                    onlineLay.setEnabled(true);
                                    //stopAnim();
                                }

                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    //
                    //    Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());


            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

    }


    public void getRequestStatus() {
        DatabaseReference keyRef = new GeoFire(FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId+"/request")).getDatabaseReference();
        keyRef.addValueEventListener(this);
    }


    protected synchronized void buildGoogleApiClient() {
        mGoogleApiClient = new GoogleApiClient.Builder(this)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build();
    }

    protected void createLocationRequest() {
        mLocationRequest = new LocationRequest();
        // Sets the desired interval for active location updates. This interval is
        // inexact. You may not receive updates at all if no location sources are available, or
        // you may receive them slower than requested. You may also receive updates faster than
        // requested if other applications are requesting location at a faster interval.
        mLocationRequest.setInterval(UPDATE_INTERVAL_IN_MILLISECONDS);
        // Sets the fastest rate for active location updates. This interval is exact, and your
        // application will never receive updates faster than this value.
        mLocationRequest.setFastestInterval(FASTEST_UPDATE_INTERVAL_IN_MILLISECONDS);
        mLocationRequest.setPriority(LocationRequest.PRIORITY_BALANCED_POWER_ACCURACY);
    }

    @Override
    protected void onStart() {
        super.onStart();
        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        client.connect();
        mGoogleApiClient.connect();
        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        Action viewAction = Action.newAction(
                Action.TYPE_VIEW, // TODO: choose an action type.
                "Map_ Page", // TODO: Define a title for the content shown.
                // TODO: If you have web page content that matches this app activity's content,
                // make sure this auto-generated web page URL is correct.
                // Otherwise, set the URL to null.
                Uri.parse("http://host/path"),
                // TODO: Make sure this auto-generated app URL is correct.
                Uri.parse("android-app://com.cog.arcaneDriver/http/host/path")
        );
        AppIndex.AppIndexApi.start(client, viewAction);
    }

    @Override
    protected void onResume() {
        super.onResume();
        mGoogleApiClient.connect();
        requestLocationUpdates(easyLocationRequest);
    }

    public void checkPermission() {
        if (ContextCompat.checkSelfPermission(Map_Activity.this,
                Manifest.permission.ACCESS_FINE_LOCATION)
                != PackageManager.PERMISSION_GRANTED) {

            ActivityCompat.requestPermissions(Map_Activity.this,
                    new String[]{Manifest.permission.ACCESS_FINE_LOCATION},
                    MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION);

            // MY_PERMISSIONS_REQUEST_ACCESS_FINE_LOCATION is an
            // app-defined int constant. The callback method gets the
            // result of the request.
        }
    }

    protected void startLocationUpdates() {
        checkPermission();
        mGoogleApiClient.connect();
        LocationServices.FusedLocationApi.requestLocationUpdates(mGoogleApiClient, mLocationRequest, this, Looper.getMainLooper());
    }

    protected void stopLocationUpdates() {
        checkPermission();
        mGoogleApiClient.connect();
        LocationServices.FusedLocationApi.removeLocationUpdates(mGoogleApiClient, this);
    }

    @Override
    protected void onStop() {
        mGoogleApiClient.disconnect();
        super.onStop();
        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        Action viewAction = Action.newAction(
                Action.TYPE_VIEW, // TODO: choose an action type.
                "Map_ Page", // TODO: Define a title for the content shown.
                // TODO: If you have web page content that matches this app activity's content,
                // make sure this auto-generated web page URL is correct.
                // Otherwise, set the URL to null.
                Uri.parse("http://host/path"),
                // TODO: Make sure this auto-generated app URL is correct.
                Uri.parse("android-app://com.cog.arcaneDriver/http/host/path")
        );
        AppIndex.AppIndexApi.end(client, viewAction);
        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        client.disconnect();
    }

    @Override
    protected void onPause() {
        super.onPause();
        // Stop location updates to save battery, but don't disconnect the GoogleApiClient object.
        if (mGoogleApiClient.isConnected()) {
            stopLocationUpdates();
        }
    }

    @Override
    public void onConnected(@Nullable Bundle bundle) {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            // TODO: Consider calling
            //    ActivityCompat#requestPermissions
            // here to request the missing permissions, and then overriding
            //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
            //                                          int[] grantResults)
            // to handle the case where the user grants the permission. See the documentation
            // for ActivityCompat#requestPermissions for more details.
            return;
        }
        LocationServices.FusedLocationApi.requestLocationUpdates(
                mGoogleApiClient,
                REQUEST,
                this);  // LocationListener
        mCurrentLocation=getLastKnownLocation();
        mMap.clear();
        if(mCurrentLocation!=null){
            LatLng latLng = new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

            System.out.println("INSIDE LOCAION CHANGE" + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
            CameraPosition cameraPosition = new CameraPosition.Builder()
                    .target(latLng)                              // Sets the center of the map to current location
                    .zoom(15)
                    .tilt(0)                                     // Sets the tilt of the camera to 0 degrees
                    .build();
            mMap.moveCamera(CameraUpdateFactory.newCameraPosition(cameraPosition));
            mMap.addMarker(new MarkerOptions()
                    .icon(BitmapDescriptorFactory.fromResource(R.mipmap.car))
                    .position(latLng));
        }
        else
        {

        }

    }

    @Override
    public void onConnectionSuspended(int i) {

    }

    @Override
    public void onLocationChanged(Location location) {
        mCurrentLocation = location;

        if(strDistanceBegin!=null)
        {

            if(strDistanceBegin.matches("distancebegin") )
            {
                if (lStart == null) {
                    lStart = mCurrentLocation;
                    lEnd = mCurrentLocation;
                } else
                    lEnd = mCurrentLocation;

                //Calling the method below updates the  live values of distance and speed to the TextViews.
                updateUI();
                //calculating the speed with getSpeed method it returns speed in m/s so we are converting it into kmph
                speed = location.getSpeed() * 18 / 5;
                //Toast.makeText(Map_Activity.this, "trip started!!", Toast.LENGTH_SHORT).show();
            }
        }
        if (mMapLocationListener != null) {
            mMapLocationListener.onLocationChanged(location);
        }
       // mMap.clear();
        if(mCurrentLocation!=null){
            LatLng latLng = new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

            System.out.println("INSIDE LOCAION CHANGE" + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
            //polyline:
            if (destLocation!=null) {
                if(tripStatus!=null&&tripStatus.matches("end")){
                    tripStatus="null";
                    if(mMap!=null)
                      mMap.clear();

                }
                else{
                    GoogleDirection.withServerKey(getString(R.string.direction_api_key))
                            .from(new LatLng(location.getLatitude(),location.getLongitude()))
                            .to(destLocation)
                            .transportMode(TransportMode.DRIVING)
                            .execute(this);
                }
            }
            else{
                if(mMap!=null)
                   mMap.clear();

                  Marker marker= mMap.addMarker(new MarkerOptions()
                        .icon(BitmapDescriptorFactory.fromResource(R.mipmap.car))
                        .position(latLng));
                  marker.setAnchor(0.5f,0.5f);
                marker.setRotation(mCurrentLocation.getBearing());
            }

        }
    }

    private void updateUI()
    {
        if(Map_Activity.p == 0)
        {
            distance = distance + (lStart.distanceTo(lEnd) / 1000.00);
            Map_Activity.endTime = System.currentTimeMillis();
            long diff = Map_Activity.endTime - Map_Activity.startTime;
            diff = TimeUnit.MILLISECONDS.toMinutes(diff);

            // Map_Activity.time.setText("Total Time: " + diff + " minutes");
            if (speed > 0.0)
                System.out.println("Speedd===>" + new DecimalFormat("#.##").format(speed) + " km/hr");
                //speed.setText("Current speed: " + new DecimalFormat("#.##").format(speed) + " km/hr");
            else
            {
               // Toast.makeText(Map_Activity.this, "DISTANCE in Map==>"+new DecimalFormat("#.###").format(distance) + " Km's.", Toast.LENGTH_SHORT).show();
            }

            lStart = lEnd;
         strTotalDistance=new DecimalFormat("#.###").format(distance);
            System.out.println("TOTAL DISTANCE+++>" + strTotalDistance);
            //SavePref.saveInt(context,"TotalDistance", strDistance);

            System.out.println("Distance in shared preference==>in mappppp" + strTotalDistance);
        }
    }

    private void getUpdateLocation(Double lat, Double lng) {

        String url = Constants.LIVEURL + "updateLocation/userid/" + driverId + "/lat/" + lat + "/long/" + lng;
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
                                String signIn_status = signIn_jsonobj.getString("status");

                                if (signIn_status.equals("Success")) {

                                    System.out.println("Location Updated!!");

                                } else if (signIn_status.equals("Fail")) {

                                }

                            } catch (JSONException e) {
                                //stopAnim();
                                onlineLay.setEnabled(true);
                                e.printStackTrace();
                            }

                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onlineLay.setEnabled(true);
                //protected static final String TAG = null;
                if (error instanceof NoConnectionError) {
                    // stopAnim();
                    //      Toast.makeText(Map_Activity.this, "An unknown network error has occured", Toast.LENGTH_SHORT).show();
                }
                VolleyLog.d(TAG, "Error: " + error.getMessage());

            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
        movieReq.setRetryPolicy(new DefaultRetryPolicy(20 * 1000, 1, 1.0f));

    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {
          }


    @Override
    public void onKeyEntered(String key, GeoLocation location) {

    }

    @Override
    public void onKeyExited(String key) {

    }

    @Override
    public void onKeyMoved(String key, GeoLocation location) {

    }

    @Override
    public void onGeoQueryReady() {

    }



    @Override
    public void onGeoQueryError(DatabaseError error) {

    }




    public void getRequestID() {
        requestReference = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("request/req_id");
        listener = requestReference.addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {

                reqID = dataSnapshot.getValue().toString();
                System.out.println("DATASNAPSHOTTT" + reqID);
                regID();
            }


            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });

    }

    private void regID() {
        if(mCurrentLocation!=null){
            upDateRequest(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());
            requestReference.removeEventListener(listener);
        }

    }

    public void gettripID()
    {
        tripReference = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept").child("trip_id");
        tripListener = tripReference.addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
            if(dataSnapshot.getValue()!=null){
                tripID = dataSnapshot.getValue().toString();
            if (tripID.isEmpty() && tripID.length() == 0) {
                if(tripState.matches("btnendClicked"))
                {}
                else{
                    showProgressDialog();
                }

            } else {

                System.out.println("THE TRID ID"+tripID);
                getState.putString("tripID",tripID);
                getState.commit();
                dismissProgressDialog();
            }

        }
                else{
                showProgressDialog();
            }

            }

            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });
    }

    @Override
    public void onDataChange(DataSnapshot dataSnapshot)
    {
        for (DataSnapshot locationSnapshot : dataSnapshot.getChildren()) {
            if(locationSnapshot.getValue()!=null&&tripState!=null){
                String location = locationSnapshot.getValue().toString();
                if (location.equals("1") && tripState.matches("endClicked")) {
                    generateNotification(getApplicationContext(),"New Request Arrivied");
                    showDialog();
                } else {
                    if (location.equals("0")) {

                       if(d!=null)
                        d.dismiss();
                    }
            }
                Log.d("Locations updated", "location: " + dataSnapshot.getValue().toString());
            }
        }

    }

    @Override
    public void onCancelled(DatabaseError databaseError) {

    }

    //get Place from Lat Lng
    private String getCompleteAddressString(double LATITUDE, double LONGITUDE)

    {
        String strAdd = "";
        Geocoder geocoder = new Geocoder(this, Locale.getDefault());
        //getlocation();
        try {
            List<Address> addresses = geocoder.getFromLocation(LATITUDE, LONGITUDE, 1);
            if (addresses != null) {
                Address returnedAddress = addresses.get(0);
                StringBuilder strReturnedAddress = new StringBuilder("");

                for (int i = 0; i < returnedAddress.getMaxAddressLineIndex(); i++) {
                    strReturnedAddress.append(returnedAddress.getAddressLine(i)).append("\n");
                }
                strAdd = strReturnedAddress.toString();
                Log.w("My Current address", "" + strReturnedAddress.toString());


                if (strsetdestination == "updatereq") {
                    //  Toast.makeText(Map_Activity.this, "ARRIVE", Toast.LENGTH_SHORT).show();
                    txtRiderDestination.setText(strReturnedAddress.toString());
                }
                else if(strsetValue.matches("coming_start")){
                    txtRiderDestination.setText(strReturnedAddress.toString());
                }

            } else {
                Log.w("My Current address", "No Address returned!");
            }
        } catch (Exception e) {
            e.printStackTrace();
            Log.w("My Current address", "Canont get Address!");
        }
        return strAdd;
    }

    public void showTripSummaryDialog() {


        dialogTripSummary.show();
        createDistanceFireBase();

        Button btnmap = (Button) dialogTripSummary.findViewById(R.id.btnGoMap);
        trip_rider_name = (TextView) dialogTripSummary.findViewById(R.id.trip_rider_name);
        txtTotalDistance= (TextView) dialogTripSummary.findViewById(R.id.text_trip_completed);
        txtTripAmount= (TextView) dialogTripSummary.findViewById(R.id.trip_amount);
        imgRiderProfile = (ImageView) dialogTripSummary.findViewById(R.id.trip_end_profile);
        txtTripdate=(TextView)dialogTripSummary.findViewById(R.id.trip_date);
        //flexibleRatingBar=(FlexibleRatingBar)dialogTripSummary.findViewById(R.id.flexibleRatingBar);
        FlexibleRatingBar driverRatingBar=(FlexibleRatingBar)dialogTripSummary.findViewById(R.id.driver_ratingBar);
        currentDateTimeString = DateFormat.getDateTimeInstance().format(new Date());
        System.out.println("CRRENT DATE TIME" + currentDateTimeString);
        // textView is the TextView view that should display it
        editor.putString("lastTripTime", currentDateTimeString);
        editor.commit();
        txtTripdate.setText(currentDateTimeString);
        getFareCalculation();
        checkAcceptStatus();
        try {

            driverRatingBar.setOnRatingBarChangeListener(new RatingBar.OnRatingBarChangeListener() {

               @Override
               public void onRatingChanged(RatingBar arg0, float rateValue, boolean arg2) {
                   // TODO Auto-generated method stub
                   Log.d("Rating", "your selected value is:"+rateValue);
                   String rating=String.valueOf(rateValue);
                   updateDriverRating(rating);
               }
           });

           trip_rider_name.setText(riderFirstName + " " + riderLastName);

            Glide.with(Map_Activity.this)
                    .load(strRiderProfile)
                    .error(R.drawable.account_circle)
                    .diskCacheStrategy(DiskCacheStrategy.NONE)
                    .skipMemoryCache(true)
                    .transform(new RoundImageTransform(Map_Activity.this))
                    .into(imgRiderProfile);
        } catch (Exception e) {
            e.printStackTrace();
        }

        btnmap.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                destLocation=null;
                distance = 0;
                strTotalDistance = "0";
                tripState="endClicked";
              //  removeRatingListener();
                mMap.clear();
                if(mCurrentLocation!=null){
                    LatLng latLng = new LatLng(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());

                    System.out.println("INSIDE ENDTRIP CHANGE" + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
                    CameraPosition cameraPosition = new CameraPosition.Builder()
                            .target(latLng)                              // Sets the center of the map to current location
                            .zoom(15)
                            .tilt(0)                                     // Sets the tilt of the camera to 0 degrees
                            .build();
                    mMap.moveCamera(CameraUpdateFactory.newCameraPosition(cameraPosition));
                    mMap.addMarker(new MarkerOptions()
                            .icon(BitmapDescriptorFactory.fromResource(R.mipmap.car))
                            .position(latLng));
                }

                Intent i=new Intent(getApplicationContext(),Map_Activity.class);
                startActivity(i);
            }
        });

    }


    private void getFareCalculation()
    {
        double strWebprice;
        if(strCategory!=null)
        {
            System.out.println("Price per KM in fare"+strWebprice_km);
            System.out.println("Price Per Min in fare"+strwebpricepermin);
            System.out.println("Max Size in fare"+strwebmaxsize);
            System.out.println("Price Fare in fare"+strwebpricefare);
            System.out.println("TollFee through out the trip"+getToll());
            float tolDistance;
            if(strTotalDistance==null)
            {
                strTotalDistance= String.valueOf(0);
                int web_price_fare= Integer.parseInt(strwebpricefare)+Integer.parseInt(strwebpricepermin);

                strTotalPrice= String.valueOf(web_price_fare);


                txtTotalDistance.setText("Total Distance : "+strTotalDistance+" KM");
                txtTripAmount.setText("$"+strTotalPrice);
                setTotalPrice(strTotalPrice);
                updateDistanceFireBase();
                getRating();
            }
            else
            {
                tolDistance= Float.parseFloat(strTotalDistance);
                strWebprice= Double.parseDouble(strWebprice_km);

                double totalAmount=(tolDistance*strWebprice)+Float.parseFloat(String.valueOf(strwebpricefare));

               //totalAmount=totalAmount+Integer.parseInt(getToll());

                totalAmount=totalAmount+Float.parseFloat(String.valueOf(strwebpricepermin));
                System.out.println("TTTTTTTTTTTTTTTTTTT"+totalAmount);
                System.out.println("TTTTTTTTTTTTTTTTTTT"+Float.parseFloat(String.valueOf(strwebpricepermin)));

                System.out.println("Calcu distanve"+tolDistance);
                System.out.println("calculated total price"+strWebprice);
                System.out.println("Total Amount too be displayed"+totalAmount);
                System.out.println("Price Fare in fare"+strwebpricefare);
                String strCalculatedDistance= String.valueOf(totalAmount);
                System.out.println("Total calu===" + strCalculatedDistance);
                double d = Double.parseDouble(String.valueOf(strCalculatedDistance));
                DecimalFormat df = new DecimalFormat("#.#");
                System.out.print(df.format(d));
                strTotalPrice=String.valueOf(df.format(d));
                txtTotalDistance.setText("Total Distance : "+strTotalDistance+" KM");
                txtTripAmount.setText("$"+strTotalPrice);
                setTotalPrice(strTotalPrice);
                //save to firebase
                updateDistanceFireBase();
                getRating();
            }

        }
    }

    public void checkAcceptStatus(){

        checkAccepRef = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId+"/accept/status");
        checkAccepRef.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                if (dataSnapshot.getValue() != null) {
                    String acceptStatus = dataSnapshot.getValue().toString();
                    if (acceptStatus != null && !acceptStatus.isEmpty()) {
                        if (acceptStatus.matches("4")) {
                            clearFirebaseData();
                        }

                    }
                }

            }

            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });
    }

    public void checktripcancelstatus(){
        checkTripCancelRef = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId+"/accept/status");
        checkTripCancelRef.addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                if (dataSnapshot.getValue() != null) {
                    String tripCancelStatus = dataSnapshot.getValue().toString();
                    if (tripCancelStatus != null && !tripCancelStatus.isEmpty()) {
                        if (tripCancelStatus.matches("5")) {
                            clearFirebaseData();
                            getCancelState();
                            if (strCacnelStatus != null) {
                                if (strCacnelStatus.equals("drivercliked")) {
                                    strCacnelStatus = "mt";
                                    Intent intent = new Intent(Map_Activity.this, Map_Activity.class);
                                    startActivity(intent);
                                } else {
                                    generateNotification(getApplicationContext(), "Rider Cancelled the Trip");
                                    showCancelDialog();
                                }

                            } else {
                                generateNotification(getApplicationContext(), "Rider Cancelled the Trip");
                                showCancelDialog();
                            }
                        }
                    }
                }
            }

            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });
    }

    public void clearFirebaseData()
    {
        DatabaseReference databaseReference = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
        Map<String, Object> taskMap = new HashMap<String, Object>();
        taskMap.put("status", "0");
        taskMap.put("trip_id", "");
        databaseReference.updateChildren(taskMap);

        DatabaseReference databaseReference1 = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("request");
        Map<String, Object> taskMap1 = new HashMap<String, Object>();
        taskMap1.put("eta", "0");
        taskMap1.put("req_id", "");
        taskMap1.put("status", "0");
        databaseReference1.updateChildren(taskMap1);

    }

    public void showDialog()
    {
        Log.w("My Current DIALOG", "INSIDE DIALOG!!!");

        //to avoid bad token exception
        if(!isFinishing())
        {
            d.show();
            //generateNotification(getApplicationContext(),"New Request Arrivied");

        }


        if (mapBitmap != null)
        {
            ByteArrayOutputStream stream = new ByteArrayOutputStream();
            mapBitmap.compress(Bitmap.CompressFormat.PNG, 100, stream);
            Glide.with(getApplicationContext()).load(stream.toByteArray()).asBitmap().centerCrop().skipMemoryCache(true).into(new BitmapImageViewTarget(requestMapView) {
                @Override
                protected void setResource(Bitmap resource)
                {
                    RoundedBitmapDrawable circularBitmapDrawable =
                            RoundedBitmapDrawableFactory.create(getApplicationContext().getResources(), resource);
                    circularBitmapDrawable.setCircular(true);
                    requestMapView.setImageDrawable(circularBitmapDrawable);
                }
            });
        }

        pwOne.resetCount();
        pwOne.startSpinning();


        progresslayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                getRequestID();
                getState.putString("tripstate","requestAccept");
                getState.commit();
                tripState="newRequest";
                DatabaseReference databaseReference = FirebaseDatabase.getInstance().getReference().child("drivers_data").child(driverId).child("accept");
                Map<String, Object> taskMap = new HashMap<String, Object>();
                taskMap.put("status", "1");
                databaseReference.updateChildren(taskMap);
                //progressLayout.setVisibility(View.GONE);
                d.dismiss();
                checktripcancelstatus();
                btnArriveNow.setEnabled(false);
                arriveNowFunction();
            }
        });

    }

    public void getAcceptState()
    {
        getRequestID();
        if(d!=null)
        d.dismiss();
        btnArriveNow.setEnabled(false);
        arriveNowFunction();
    }

    public void getStartState(){
        getRequestID();
        arriveNowLayout.setVisibility(View.GONE);
        startTripLayout.setVisibility(View.VISIBLE);
        destinationLayout.setVisibility(View.VISIBLE);
        toolbarLayout.setVisibility(View.GONE);
        bottomBar.setVisibility(View.GONE);
        updateArriveRequest();
    }

    public void getendState(){
      //  removeTripListener();
        int previoustollfee=state.getInt("tollfee",0);

        previousToll = previoustollfee;

        arriveNowLayout.setVisibility(View.GONE);
        endTripLayout.setVisibility(View.VISIBLE);
        destinationLayout.setVisibility(View.VISIBLE);
        toolbarLayout.setVisibility(View.GONE);
        bottomBar.setVisibility(View.GONE);
        startUpdateTrip();
        strDistanceBegin="distancebegin";
        distance=0;

    }

    public void removeRatingListener()
    {
        ratingReference.removeEventListener(ratingListener);
    }

    public void showProgressDialog(){

                progressDialog = new ProgressDialog(this);
                progressDialog.setProgress(ProgressDialog.STYLE_SPINNER);
                progressDialog.setIndeterminate(false);
                progressDialog.setCancelable(false);
                progressDialog.setMessage("Accepting...");
                progressDialog.show();

        }

    public void dismissProgressDialog()
    {
        btnArriveNow.setEnabled(true);
        if (progressDialog != null) {
            progressDialog.dismiss();
            progressDialog = null;
        }
    }

    //generateNotifications
    private static void generateNotification(Context context,String message) {

        //Some Vars
        final int NOTIFICATION_ID = 1; //this can be any int
        String title = "ArcaneLite Driver";
        Uri uri= RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);

        //Building the Notification
        Bitmap largeIcon = BitmapFactory.decodeResource(context.getResources(), R.mipmap.ic_launcher);
        NotificationCompat.Builder builder = new NotificationCompat.Builder(context);
        builder.setSmallIcon(R.mipmap.ic_launcher);
        builder.setLargeIcon(largeIcon);
        builder.setContentTitle(title);
        builder.setContentText(message);

        builder.setLights(Color.RED, 3000, 3000);
        builder.setSound(uri);
        builder.setAutoCancel(true);

        builder.getNotification().flags= Notification.DEFAULT_LIGHTS | Notification.FLAG_AUTO_CANCEL;;


        final NotificationManager notificationManager = (NotificationManager) context.getSystemService(
                NOTIFICATION_SERVICE);
        notificationManager.notify(NOTIFICATION_ID, builder.build());

        final Timer timer = new Timer();
        timer.schedule(new TimerTask() {
            @Override
            public void run() {
                notificationManager.cancel(NOTIFICATION_ID);
                timer.cancel();
            }
        }, 10000, 1000);
    }

    public void generateSimple()
    {
        Uri uri= RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        //Building the Notification
        String title = "ArcaneLite Driver";
        Bitmap largeIcon = BitmapFactory.decodeResource(getApplicationContext().getResources(), R.mipmap.ic_launcher);
        NotificationCompat.Builder mBuilder =
            new NotificationCompat.Builder(this)
                    .setSmallIcon(R.drawable.app_icon)
                    .setLargeIcon(largeIcon)
                    .setContentTitle(title)
                    .setSound(uri)
                    .setContentText("New Request Arrivied");



// Gets an instance of the NotificationManager service//

        NotificationManager mNotificationManager =

                (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);

//When you issue multiple notifications about the same type of event, it’s best practice for your app to try to update an existing notification with this new information, rather than immediately creating a new notification. If you want to update this notification at a later date, you need to assign it an ID. You can then use this ID whenever you issue a subsequent notification. If the previous notification is still visible, the system will update this existing notification, rather than create a new one. In this example, the notification’s ID is 001//

        mNotificationManager.notify(001, mBuilder.build());

                //mNotificationManager.notify(001, mBuilder.build());

    }


    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
    @Override
    public void onBackPressed()
    {
        if (doubleBackToExitPressedOnce)
        {
            super.onBackPressed();
            this.finishAffinity();
            int pid = android.os.Process.myPid();
            android.os.Process.killProcess(pid);
            System.exit(0);
            finish();
            return;
        }

        this.doubleBackToExitPressedOnce = true;
        Toast.makeText(this, "Please click BACK again to exit the app", Toast.LENGTH_SHORT).show();

        new Handler().postDelayed(new Runnable() {

            @Override
            public void run() {
                doubleBackToExitPressedOnce = false;
            }
        }, 2000);
    }


    @Override
    public void activate(OnLocationChangedListener onLocationChangedListener) {
        mMapLocationListener = onLocationChangedListener;
    }

    @Override
    public void deactivate() {
        mMapLocationListener=null;
    }

    @Override
    public void onLocationPermissionGranted() {

    }

    @Override
    public void onLocationPermissionDenied() {

    }

    @Override
    public void onLocationReceived(Location location) {
        System.out.println(location.getProvider() + "," + location.getLatitude() + "," + location.getLongitude());
        mCurrentLocation=location;
        //Update current location in Firebase
        onLocationChanged(location);



        if (driverId != null && !driverId.isEmpty() && !driverId.trim().matches("")) {
            SharedPreferences prefs = getSharedPreferences(Constants.MY_PREFS_NAME, MODE_PRIVATE);
            onlinecheck = prefs.getString("onlinestatus", null);
            if (onlinecheck != null && !onlinecheck.isEmpty()) {

                if (onlinecheck.matches("online"))
                {
                    getUpdateLocation(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude());
                    this.geoFire.setLocation(driverId, new GeoLocation(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude()), new GeoFire.CompletionListener() {
                        @Override
                        public void onComplete(String key, DatabaseError error) {
                            if (error != null)
                            {
                                System.err.println("There was an error saving the location to GeoFire: " + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
                            } else
                            {
                                System.out.println("Location saved on server successfully!");
                            }
                        }
                    });
                } else
                {
                    getUpdateLocation(0.0, 0.0);
                    this.geoFire.offlineLocation(driverId, new GeoLocation(0.0,0.0), new GeoFire.CompletionListener() {
                        @Override
                        public void onComplete(String key, DatabaseError error)
                        {
                            if (error != null)
                            {
                                System.err.println("There was an error saving the location to GeoFire: " + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
                            } else
                            {
                                System.out.println("Location saved on server successfully!");
                            }
                        }
                    });
                }
            }
        }
        else {
            this.geoFire.setLocation("geolocation", new GeoLocation(mCurrentLocation.getLatitude(), mCurrentLocation.getLongitude()), new GeoFire.CompletionListener() {
                @Override
                public void onComplete(String key, DatabaseError error) {
                    if (error != null) {
                        System.err.println("There was an error saving the location to GeoFire: " + mCurrentLocation.getLatitude() + mCurrentLocation.getLongitude());
                    } else {
                        System.out.println("Location saved on server successfully!");
                    }
                }
            });
        }

    }



    @Override
    public void onLocationProviderEnabled() {

    }

    @Override
    public void onLocationProviderDisabled() {
        this.finishAffinity();
        int pid = android.os.Process.myPid();
        android.os.Process.killProcess(pid);
        System.exit(0);
        finish();
        return;
    }

    @Override
    public void onDirectionSuccess(Direction direction, String rawBody) {
        if(direction!=null){
            if (direction.isOK()) {
                mMap.clear();
        /*    CameraPosition cameraPosition = new CameraPosition.Builder()
                    .target(new LatLng(mCurrentLocation.getLatitude(),mCurrentLocation.getLongitude()))                              // Sets the center of the map to current location
                    .bearing(mCurrentLocation.getBearing())// Sets the orientation of the camera to east
                    .zoom(12) Mam Could you please reply?My father wants to move with furthrr if he know these details for bank loan.
                    .build();                                    // Creates a CameraPosition from the builder

            mMap.moveCamera(CameraUpdateFactory.newLatLng(cameraPosition));*/
                if(mCurrentLocation.hasBearing()){
                    //updateCameraBearing(mMap,mCurrentLocation.getBearing());
                }

                if(orginLocation!=null&&destLocation!=null)
                {
                    if(strsetValue.matches("coming_start")){
                        // before loop:
                        marker= mMap.addMarker(new MarkerOptions().position(new LatLng(mCurrentLocation.getLatitude(),mCurrentLocation.getLongitude())).icon(BitmapDescriptorFactory.fromResource(R.mipmap.car)));
                        marker.setAnchor(0.5f,0.5f);
                        marker.setRotation(mCurrentLocation.getBearing());
                        marker1=mMap.addMarker(new MarkerOptions().position(destLocation).icon(BitmapDescriptorFactory.fromResource(R.mipmap.ub__ic_pin_dropoff)).flat(true));


                    }
                    else{
                        Marker marker= mMap.addMarker(new MarkerOptions().position(new LatLng(mCurrentLocation.getLatitude(),mCurrentLocation.getLongitude())).icon(BitmapDescriptorFactory.fromResource(R.mipmap.car)));
                        marker.setAnchor(0.5f,0.5f);
                        marker.setRotation(mCurrentLocation.getBearing());
                        mMap.addMarker(new MarkerOptions().position(destLocation).icon(BitmapDescriptorFactory.fromResource(R.mipmap.ub__ic_pin_pickup)).flat(true));
                    }

                    ArrayList<LatLng> directionPositionList = direction.getRouteList().get(0).getLegList().get(0).getDirectionPoint();
                    mMap.addPolyline(DirectionConverter.createPolyline(this, directionPositionList, 3, Color.BLACK));
                }
            }
        }
    }

    @Override
    public void onDirectionFailure(Throwable t) {
         System.out.println("DIRECTION "+t);
    }


    private ServiceConnection sc = new ServiceConnection() {
        @Override
        public void onServiceConnected(ComponentName name, IBinder service) {
            LocationService.LocalBinder binder = (LocationService.LocalBinder) service;
            myService = binder.getService();
            distanceStatus = true;
        }

        @Override
        public void onServiceDisconnected(ComponentName name) {
            distanceStatus = false;
        }
    };

    void bindService() {
        if (distanceStatus == true)
            return;
        Intent i = new Intent(getApplicationContext(), LocationService.class);
        bindService(i, sc, BIND_AUTO_CREATE);
        distanceStatus = true;
        startTime = System.currentTimeMillis();
    }

    void unbindService() {
        if (distanceStatus == false)
            return;
        Intent i = new Intent(getApplicationContext(), LocationService.class);
        unbindService(sc);
        distanceStatus = false;
    }



    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (distanceStatus == true)
            unbindService();
    }

    private void updateCameraBearing(GoogleMap googleMap, float bearing) {
        if ( googleMap == null) return;
        CameraPosition camPos = CameraPosition
                .builder(
                        googleMap.getCameraPosition() // current Camera
                )
                .bearing(bearing)
                .build();
        googleMap.moveCamera(CameraUpdateFactory.newCameraPosition(camPos));
    }


}

