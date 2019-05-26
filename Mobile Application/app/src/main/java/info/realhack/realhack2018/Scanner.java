package info.realhack.realhack2018;

import android.Manifest;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.widget.Toast;

import com.google.zxing.Result;

import java.net.URISyntaxException;

import io.socket.client.IO;
import io.socket.client.Socket;
import me.dm7.barcodescanner.zxing.ZXingScannerView;

import static info.realhack.realhack2018.MainActivity.socketIP;
import static info.realhack.realhack2018.MainActivity.sockerPort;

public class Scanner extends AppCompatActivity implements ZXingScannerView.ResultHandler {
    private ZXingScannerView zXingScannerView;
    private static Socket mSocket;
    int requestCode = 100;
    String preRes = "00000000";


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

            try {
                mSocket = IO.socket("http://"+ socketIP+ ":" + sockerPort);
            } catch (URISyntaxException e) {
                Log.i("socketio",e.getMessage());
            }

        mSocket.connect();
        Toast.makeText(getApplicationContext(), "Connected to http://"+socketIP+ ":" + sockerPort, Toast.LENGTH_SHORT).show();
        zXingScannerView =new ZXingScannerView(getApplicationContext());
        setContentView(zXingScannerView);
        zXingScannerView.setResultHandler(this);
        if(ActivityCompat.checkSelfPermission(this, Manifest.permission.CAMERA)
                != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(
                    Scanner.this,
                    new String[]{Manifest.permission.CAMERA,},requestCode);
            zXingScannerView.startCamera();
        }else{
            zXingScannerView.startCamera();
        }
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        mSocket.disconnect();
    }

    @Override
    public void handleResult(Result result) {
        if(!result.getText().equals(preRes)){
            mSocket.emit("Request", MainActivity.tag + result.getText());
            Toast.makeText(getApplicationContext(), MainActivity.tag + result.getText(), Toast.LENGTH_SHORT).show();
            preRes = result.getText();
        }
        if(ActivityCompat.checkSelfPermission(this, Manifest.permission.CAMERA)
                != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(
                    Scanner.this,
                    new String[]{Manifest.permission.CAMERA,},requestCode);
            zXingScannerView.resumeCameraPreview(this);
        }else {
            zXingScannerView.resumeCameraPreview(this);
        }

    }


}
