<?php

// Create connection
include("conn.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'users' table
$sql = "SELECT `name`, `email`, `phone` FROM users WHERE `id`=0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create an array to store the data
    $data = array();

    // Loop through each row of the result set
    while($row = $result->fetch_assoc()) {
        // Add the row to the data array
        $data[] = $row;
    }

    // Convert the data array to JSON format and return it
    echo json_encode($data);
} else {
    // If no data is found, return an empty array in JSON format
    echo json_encode(array());
}

// Close the database connection
$conn->close();
?>



<!-- =============================================================================== -->
<!-- ============================= Gradel Dependicies ============================= -->
<!-- =============================================================================== -->

dependencies {
    // AndroidX libraries
    implementation 'androidx.appcompat:appcompat:1.4.0'
    implementation 'androidx.constraintlayout:constraintlayout:2.1.1'
    implementation 'androidx.recyclerview:recyclerview:1.2.1'
    implementation 'androidx.cardview:cardview:1.0.0'

    // Volley for network requests
    implementation 'com.android.volley:volley:1.2.0'

    // Gson for JSON parsing
    implementation 'com.google.code.gson:gson:2.8.8'
}




<!-- =============================================================================== -->
<!-- ================================= Layout code ================================= -->
<!-- =============================================================================== -->

<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <ScrollView
        android:layout_width="match_parent"
        android:layout_height="match_parent">

        <TableLayout
            android:id="@+id/tableLayout"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:stretchColumns="*"
            android:padding="16dp">

            <TableRow
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:background="#c3c3c3">

                <TextView
                    android:id="@+id/nameHeaderTextView"
                    android:layout_width="0dp"
                    android:layout_height="wrap_content"
                    android:layout_weight="1"
                    android:text="Name"
                    android:textColor="#000000"
                    android:textStyle="bold" />

                <TextView
                    android:id="@+id/emailHeaderTextView"
                    android:layout_width="0dp"
                    android:layout_height="wrap_content"
                    android:layout_weight="1"
                    android:text="Email"
                    android:textColor="#000000"
                    android:textStyle="bold" />

                <TextView
                    android:id="@+id/phoneHeaderTextView"
                    android:layout_width="0dp"
                    android:layout_height="wrap_content"
                    android:layout_weight="1"
                    android:text="Phone"
                    android:textColor="#000000"
                    android:textStyle="bold" />

            </TableRow>

        </TableLayout>

    </ScrollView>

</RelativeLayout>



<!-- =============================================================================== -->
<!-- ============================= Java Activity code ============================= -->
<!-- =============================================================================== -->
<!-- ========================== Change url from "final String API_URL" ========================== -->
<!-- =============================================================================== -->

import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class CustomersActivity extends AppCompatActivity {

    private static final String API_URL = "http://yourapiurl.com/get_customers.php";
    private TableLayout tableLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_customers);

        tableLayout = findViewById(R.id.tableLayout);

        // Fetch data from the API in a background thread
        new FetchDataTask().execute(API_URL);
    }

    private class FetchDataTask extends AsyncTask<String, Void, JSONArray> {

        @Override
        protected JSONArray doInBackground(String... params) {

            try {
                URL url = new URL(params[0]);
                HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                connection.setRequestMethod("GET");

                BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                StringBuilder response = new StringBuilder();
                String line;
                while ((line = reader.readLine()) != null) {
                    response.append(line);
                }
                reader.close();
                connection.disconnect();

                // Parse the JSON response and return it as a JSONArray
                return new JSONArray(response.toString());
            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                return null;
            }
        }

        @Override
        protected void onPostExecute(JSONArray result) {
            if (result != null) {
                // Populate the table with the data
                for (int i = 0; i < result.length(); i++) {
                    try {
                        JSONObject obj = result.getJSONObject(i);

                        String name = obj.getString("name");
                        String email = obj.getString("email");
                        String phone = obj.getString("phone");

                        TableRow row = new TableRow(getApplicationContext());

                        TextView nameTextView = new TextView(getApplicationContext());
                        nameTextView.setText(name);
                        row.addView(nameTextView);

                        TextView emailTextView = new TextView(getApplicationContext());
                        emailTextView.setText(email);
                        row.addView(emailTextView);

                        TextView phoneTextView = new TextView(getApplicationContext());
                        phoneTextView.setText(phone);
                        row.addView(phoneTextView);

                        tableLayout.addView(row);
                    } catch (JSONException e) {
                        Log.e("Error", e.getMessage());
                    }
                }
            }
        }
    }
}
