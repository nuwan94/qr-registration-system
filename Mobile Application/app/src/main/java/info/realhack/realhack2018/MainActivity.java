package info.realhack.realhack2018;

import android.content.Intent;

import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.app.ActionBar;

import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioGroup;


public class MainActivity extends AppCompatActivity {
    public static String tag = "c-";
    public static String socketIP = "";
    public static String sockerPort = "";
    private RadioGroup rg;

    final String MY_PREFS_NAME = "SocketDetails";
    SharedPreferences prefs;
    EditText socketEdit, portEdit;

    protected void savePreferences(){
        SharedPreferences.Editor editor = prefs.edit();
        editor.putString("ip", String.valueOf(socketEdit.getText()));
        editor.putString("port", String.valueOf(portEdit.getText()));
        editor.apply();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        ActionBar actionBar = getSupportActionBar();
        actionBar.setDisplayShowHomeEnabled(true);
        actionBar.setIcon(R.mipmap.ic_launcher);

        prefs = getSharedPreferences(MY_PREFS_NAME, MODE_PRIVATE);
        socketEdit = findViewById(R.id.scoketEdit);
        portEdit = findViewById(R.id.portEdit);

        String savedSocketDetails = prefs.getString("ip", null);
        if (savedSocketDetails != null) {
            socketEdit.setText(prefs.getString("ip", "192.168.43.104"));
            portEdit.setText(prefs.getString("port", "3000"));
        }

        socketEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                savePreferences();
            }
        });


        portEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                savePreferences();
            }
        });

        rg = findViewById(R.id.scanOption);
        rg.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(RadioGroup group, int checkedId) {
               tag  = findViewById(rg.getCheckedRadioButtonId()).getTag().toString();
            }
        });

        Button scanBtn = findViewById(R.id.scanMe);
        scanBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                socketIP = String.valueOf(socketEdit.getText());
                sockerPort = String.valueOf(portEdit.getText());
                Intent scanActivity = new Intent(getApplicationContext(),Scanner.class);
                startActivity(scanActivity);
            }
        });
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
    }

    @Override
    protected void onPause() {
        super.onPause();
    }

}
