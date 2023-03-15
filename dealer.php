<?php

// Create connection
include("conn.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'users' table
$sql = "SELECT `name`, `email`, `phone` FROM users WHERE `id`=2";
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

implementation 'com.android.support:design:28.0.0'


<!-- =============================================================================== -->
<!-- ================================= Layout Code ================================= -->
<!-- =============================================================================== -->


<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <TableLayout
        android:id="@+id/table_layout"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_margin="16dp"
        android:stretchColumns="*">

        <TableRow
            android:layout_width="match_parent"
            android:layout_height="wrap_content">

            <TextView
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:text="Name"
                android:textStyle="bold"
                android:padding="8dp" />

            <TextView
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:text="Email"
                android:textStyle="bold"
                android:padding="8dp" />

            <TextView
                android:layout_width="wrap_content"
                android:layout_height="wrap_content"
                android:text="Phone"
                android:textStyle="bold"
                android:padding="8dp" />

        </TableRow>

    </TableLayout>

</RelativeLayout>



<!-- =============================================================================== -->
<!-- ============================= Java Activity code ============================= -->
<!-- =============================================================================== -->
<!-- ========================== Change url from "final String API_URL" ========================== -->
<!-- =============================================================================== -->

import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class Adminpanel extends AppCompatActivity {

    private static final String API_URL = "http://localhost/api.php";
    private TableLayout tableLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_adminpanel);

        tableLayout = findViewById(R.id.table_layout);

        // Fetch data from the API
        String data = fetchDataFromAPI();

        // Parse JSON data and populate table
        populateTable(data);
    }

    private String fetchDataFromAPI() {
        StringBuilder result = new StringBuilder();
        HttpURLConnection connection = null;

        try {
            URL url = new URL(API_URL);
            connection = (HttpURLConnection) url.openConnection();
            connection.setRequestMethod("GET");

            BufferedReader reader = new BufferedReader(
                    new InputStreamReader(connection.getInputStream()));
            String line;

            while ((line = reader.readLine()) != null) {
                result.append(line);
            }

            reader.close();
        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            if (connection != null) {
                connection.disconnect();
            }
        }

        return result.toString();
    }

    private void populateTable(String data) {
        try {
            JSONArray jsonArray = new JSONArray(data);

            for (int i = 0; i < jsonArray.length(); i++) {
                JSONObject jsonObject = jsonArray.getJSONObject(i);

                String name = jsonObject.getString("name");
                String email = jsonObject.getString("email");
                String phone = jsonObject.getString("phone");

                TableRow row = new TableRow(this);

                TextView nameTextView = new TextView(this);
                nameTextView.setText(name);
                row.addView(nameTextView);

                TextView emailTextView = new TextView(this);
                emailTextView.setText(email);
                row.addView(emailTextView);

                TextView phoneTextView = new TextView(this);
                phoneTextView.setText(phone);
                row.addView(phoneTextView);

                tableLayout.addView(row);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }
}
