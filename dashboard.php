<?php

// Create connection
include("conn.php");
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'vehicle' table
$sql = "SELECT `manufacturer`, `model`, `year`, `price`, `color`, `mileage`, `fuel_type` FROM vehicle";
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
    // other dependencies here
    implementation 'com.android.volley:volley:1.2.1'
    implementation 'com.google.code.gson:gson:2.8.8'
}




<!-- =============================================================================== -->
<!-- ================================= Layout code ================================= -->
<!-- =============================================================================== -->

<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical">

    <ScrollView
        android:layout_width="match_parent"
        android:layout_height="wrap_content">

        <TableLayout
            android:id="@+id/table_layout"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:stretchColumns="*"
            android:shrinkColumns="*"
            android:background="@android:color/white">

            <TableRow
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:background="@android:color/darker_gray">

                <TextView
                    android:text="Manufacturer"
                    android:textColor="@android:color/white"
                    android:textStyle="bold"
                    android:padding="16dp" />

                <TextView
                    android:text="Model"
                    android:textColor="@android:color/white"
                    android:textStyle="bold"
                    android:padding="16dp" />

                <TextView
                    android:text="Year"
                    android:textColor="@android:color/white"
                    android:textStyle="bold"
                    android:padding="16dp" />

                <TextView
                    android:text="Price"
                    android:textColor="@android:color/white"
                    android:textStyle="bold"
                    android:padding="16dp" />

                <TextView
                    android:text="Color"
                    android:textColor="@android:color/white"
                    android:textStyle="bold"
                    android:padding="16dp" />

                <TextView
                    android:text="Mileage"
                    android:textColor="@android:color/white"
                    android:textStyle="bold"
                    android:padding="16dp" />

                <TextView
                    android:text="Fuel Type"
                    android:textColor="@android:color/white"
                    android:textStyle="bold"
                    android:padding="16dp" />
            </TableRow>

        </TableLayout>
    </ScrollView>
</LinearLayout>



<!-- =============================================================================== -->
<!-- ============================= Java Activity code ============================= -->
<!-- =============================================================================== -->
<!-- ========================== Change url from "URL url" ========================== -->
<!-- =============================================================================== -->

import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.ViewGroup;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Iterator;

public class DashboardActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dashboard);

        // Initialize and execute the AsyncTask to fetch data from the PHP API
        new GetDataTask().execute();
    }

    private class GetDataTask extends AsyncTask<Void, Void, String> {

        @Override
        protected String doInBackground(Void... params) {
            String result = "";
            HttpURLConnection urlConnection = null;
            try {
                URL url = new URL("http://your-php-api-url.com");
                urlConnection = (HttpURLConnection) url.openConnection();

                InputStream in = urlConnection.getInputStream();
                BufferedReader reader = new BufferedReader(new InputStreamReader(in));
                StringBuilder stringBuilder = new StringBuilder();
                String line;
                while ((line = reader.readLine()) != null) {
                    stringBuilder.append(line);
                }
                result = stringBuilder.toString();

            } catch (IOException e) {
                e.printStackTrace();
            } finally {
                if (urlConnection != null) {
                    urlConnection.disconnect();
                }
            }
            return result;
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);

            // Parse the JSON data and display it in a table layout
            try {
                JSONArray jsonArray = new JSONArray(result);
                TableLayout tableLayout = findViewById(R.id.table_layout);

                // Create table rows and add data to them
                for (int i = 0; i < jsonArray.length(); i++) {
                    JSONObject jsonObject = jsonArray.getJSONObject(i);
                    TableRow tableRow = new TableRow(DashboardActivity.this);
                    tableRow.setLayoutParams(new TableRow.LayoutParams(
                            ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));

                    // Iterate through the JSON object and add each value to a table cell
                    Iterator<String> keys = jsonObject.keys();
                    while (keys.hasNext()) {
                        String key = keys.next();
                        TextView textView = new TextView(DashboardActivity.this);
                        textView.setLayoutParams(new TableRow.LayoutParams(
                                ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT));
                        textView.setPadding(16, 16, 16, 16);
                        textView.setText(jsonObject.getString(key));
                        tableRow.addView(textView);
                    }
                    tableLayout.addView(tableRow);
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }
}
