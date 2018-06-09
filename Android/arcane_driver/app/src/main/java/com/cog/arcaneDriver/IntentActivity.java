package com.cog.arcaneDriver;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;

/**
 * Created by test on 19/12/16.
 */
public class IntentActivity extends Activity {

    public  IntentActivity aci;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);



    }

public void start()
{
    Intent intent=new Intent(IntentActivity.this,LaunchActivity_.class);
    startActivity(intent);
}


    public void startLaunch(Context context, Class<?> cls) {
        Intent intent = new Intent(context, cls);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

        context.startActivity(intent);
    }

}