<?php
include("conn.php");


        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $email = $_POST["email"];
            $pass = $_POST["pass"];

             $sel = "SELECT * FROM `users` WHERE `emalil` = '$email' AND `pass` = '$pass'";
             $res = mysqli_query($conn, $sel);
             if(mysqli_num_rows($res)){
                while ($row = mysqli_fetch_array($res)) {
                    $role = $row["role"];
                    if ($role == 0) {
                        echo "User Customer";
                    }
                    else if ($role == 1) {
                        echo "User Admin";
                    }
                    else if ($role == 2) {
                        echo "User Dealer";
                    }
                    else {
                        echo "NO DATA FOUND";
                    }
                }
             }
             else{
                echo "NO DATA FOUND";
             }
        }
        else{
            echo "no reuquest";
        }
    
?>


<!-- =============================================================================== -->
<!-- ============================= Gradel Dependicies ============================= -->
<!-- =============================================================================== -->

    implementation 'com.android.support:appcompat-v7:28.0.0'
    implementation 'com.android.support.constraint:constraint-layout:2.0.4'
    testImplementation 'junit:junit:4.13.2'
    androidTestImplementation 'com.android.support.test:runner:1.0.2'
    androidTestImplementation 'com.android.support.test.espresso:espresso-core:3.0.2'
    implementation 'com.android.volley:volley:1.2.1'



<!-- =============================================================================== -->
<!-- =============================== Layout xml code =============================== -->
<!-- =============================================================================== -->


    <TextView
        android:layout_width="match_parent"
        android:layout_marginTop="40dp"
        android:layout_marginBottom="40dp"
        android:layout_height="wrap_content"
        android:text="LOGIN"
        android:paddingLeft="10dp"
        android:paddingRight="10dp"
        android:textAlignment="center"
        android:textSize="50sp"/>

    <EditText
        android:id="@+id/editTextTextEmailAddress2"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:ems="10"
        android:hint="Email"
        android:paddingLeft="10dp"
        android:paddingRight="10dp"
        android:layout_marginTop="40dp"
        android:inputType="textEmailAddress" />

    <EditText
        android:id="@+id/editTextTextPassword2"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:hint="Password"
        android:ems="10"
        android:layout_marginTop="40dp"
        android:inputType="textPassword" />

    <Button
        android:id="@+id/mylogin"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:text="LOGIN"/>



<!-- =============================================================================== -->
<!-- ============================= Java Activity code ============================= -->
<!-- =============================================================================== -->
<!-- ================== Change url from "final String LOGINAPI" ================== -->
<!-- =============================================================================== -->

package com.example.mysecond_reg;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.HashMap;
import java.util.Map;

public class Login extends AppCompatActivity {

    private Button LOGIN;
    private EditText email, pass;
    final String LOGINAPI = "http://192.168.18.32/MVFP/login.php";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        LOGIN = (Button) findViewById(R.id.mylogin);
        email = (EditText) findViewById(R.id.editTextTextEmailAddress2);
        pass = (EditText) findViewById(R.id.editTextTextPassword2);

        LOGIN.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mylogin(email.getText().toString(), pass.getText().toString());
            }
        });

    }

    public void mylogin(String email, String pass){
        StringRequest REQ = new StringRequest(Request.Method.POST, LOGINAPI, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                if (response.equals("User Customer")) {
                    Intent mydash = new Intent(getApplicationContext(), Dashboard.class);
                    startActivity(mydash);
                }
                else if (response.equals("User Admin")) {
                    Intent admindash = new Intent(getApplicationContext(), Admin_Dashboard.class);
                    startActivity(admindash);
                }
                else if (response.equals("User Dealer")) {
                    Intent dealerdash = new Intent(getApplicationContext(), Dealer_Dashboard.class);
                    startActivity(dealerdash);
                }
                else {
                    Toast.makeText(Login.this, "Invalid Email or Password", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(Login.this, error.toString(), Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> map = new HashMap<>();
                map.put("email", email);
                map.put("pass", pass);
                return map;
            }
        };
        RequestQueue reqst = Volley.newRequestQueue(getApplicationContext());
        reqst.add(REQ);
    }
}