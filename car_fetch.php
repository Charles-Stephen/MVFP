<?php

include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = "SELECT * FROM `vehicle`";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $response = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $vehicle = array();
            $vehicle["id"] = $row["id"];
            $vehicle["manufacturer"] = $row["manufacturer"];
            $vehicle["model"] = $row["model"];
            $vehicle["year"] = $row["year"];
            $vehicle["price"] = $row["price"];
            $vehicle["color"] = $row["color"];
            $vehicle["mileage"] = $row["mileage"];
            $vehicle["fuel_type"] = $row["fuel_type"];
            $vehicle["description"] = $row["description"];
            $vehicle["image"] = $row["image"];

            array_push($response, $vehicle);
        }

        echo json_encode(array("error" => false, "vehicles" => $response));
    } else {
        echo json_encode(array("error" => true, "message" => "No vehicles found"));
    }
} else {
    echo json_encode(array("error" => true, "message" => "Invalid request method"));
}

mysqli_close($conn);

?>


<!-- =============================================================================== -->
<!-- ============================= Gradel Dependicies ============================= -->
<!-- =============================================================================== -->

dependencies {
    // RecyclerView
    implementation 'androidx.recyclerview:recyclerview:1.2.1'

    // CardView
    implementation 'androidx.cardview:cardview:1.0.0'

    // Glide
    implementation 'com.github.bumptech.glide:glide:4.12.0'

    // Retrofit
    implementation 'com.squareup.retrofit2:retrofit:2.9.0'
    implementation 'com.squareup.retrofit2:converter-gson:2.9.0'

    // OkHttp Logging Interceptor
    implementation 'com.squareup.okhttp3:logging-interceptor:4.9.2'

    // Gson
    implementation 'com.google.code.gson:gson:2.8.8'
}

<!-- =============================================================================== -->
<!-- ================================= Layout code ================================= -->
<!-- =============================================================================== -->

<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:id="@+id/activity_main_layout"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    android:padding="16dp"
    tools:context=".MainActivity">

    <TextView
        android:id="@+id/title_text_view"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_marginBottom="16dp"
        android:text="Motor Finance"
        android:textSize="24sp" />

    <androidx.recyclerview.widget.RecyclerView
        android:id="@+id/vehicle_recycler_view"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_marginBottom="16dp"
        android:scrollbars="vertical" />

    <TextView
        android:id="@+id/no_data_text_view"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:gravity="center"
        android:text="No data available"
        android:textSize="18sp"
        android:visibility="gone" />

    <ProgressBar
        android:id="@+id/loading_progress_bar"
        style="?android:attr/progressBarStyleLarge"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_gravity="center"
        android:indeterminate="true"
        android:visibility="gone" />

</LinearLayout>


<!-- =============================================================================== -->
<!-- ============================= Java Activity code ============================= -->
<!-- =============================================================================== -->
<!-- ================== Change url from "String API_URL" ================== -->
<!-- =============================================================================== -->

import android.os.AsyncTask;
import android.os.Bundle;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;

public class MainActivity extends AppCompatActivity {

    private static final String API_URL = "http://your_api_url_here.com/fetch_data.php";

    private TableLayout tableLayout;
    private ArrayList<Vehicle> vehicles;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        tableLayout = findViewById(R.id.table_layout);

        new FetchDataTask().execute();
    }

    private void addHeaders() {
        TableRow rowHeader = new TableRow(this);

        TextView idHeader = new TextView(this);
        idHeader.setText("ID");
        rowHeader.addView(idHeader);

        TextView manufacturerHeader = new TextView(this);
        manufacturerHeader.setText("Manufacturer");
        rowHeader.addView(manufacturerHeader);

        TextView modelHeader = new TextView(this);
        modelHeader.setText("Model");
        rowHeader.addView(modelHeader);

        TextView yearHeader = new TextView(this);
        yearHeader.setText("Year");
        rowHeader.addView(yearHeader);

        TextView priceHeader = new TextView(this);
        priceHeader.setText("Price");
        rowHeader.addView(priceHeader);

        TextView colorHeader = new TextView(this);
        colorHeader.setText("Color");
        rowHeader.addView(colorHeader);

        TextView mileageHeader = new TextView(this);
        mileageHeader.setText("Mileage");
        rowHeader.addView(mileageHeader);

        TextView fuelTypeHeader = new TextView(this);
        fuelTypeHeader.setText("Fuel Type");
        rowHeader.addView(fuelTypeHeader);

        TextView descriptionHeader = new TextView(this);
        descriptionHeader.setText("Description");
        rowHeader.addView(descriptionHeader);

        tableLayout.addView(rowHeader);
    }

    private void addRows() {
        for (int i = 0; i < vehicles.size(); i++) {
            TableRow row = new TableRow(this);

            TextView id = new TextView(this);
            id.setText(String.valueOf(vehicles.get(i).getId()));
            row.addView(id);

            TextView manufacturer = new TextView(this);
            manufacturer.setText(vehicles.get(i).getManufacturer());
            row.addView(manufacturer);

            TextView model = new TextView(this);
            model.setText(vehicles.get(i).getModel());
            row.addView(model);

            TextView year = new TextView(this);
            year.setText(String.valueOf(vehicles.get(i).getYear()));
            row.addView(year);

            TextView price = new TextView(this);
            price.setText(String.valueOf(vehicles.get(i).getPrice()));
            row.addView(price);

            TextView color = new TextView(this);
            color.setText(vehicles.get(i).getColor());
            row.addView(color);

            TextView mileage = new TextView(this);
            mileage.setText(String.valueOf(vehicles.get(i).getMileage()));
            row.addView(mileage);

            TextView fuelType = new TextView(this);
            fuelType.setText(vehicles.get(i).getFuelType());
            row.addView(fuelType);

            TextView description = new TextView(this);
            description.setText(vehicles.get(i).getDescription());
            row.addView(description);

            tableLayout.addView(row);
        }
    }

    private class FetchDataTask extends AsyncTask<Void, Void, Void> {

        private String responseString;

        @Override
        protected Void doInBackground(Void... params) {
            try {
                URL url = new URL(API_URL);
                HttpURLConnection connection = (HttpURLConnection) url.openConnection();
            connection.setRequestMethod("GET");
            connection.connect();

            int responseCode = connection.getResponseCode();
            if (responseCode == HttpURLConnection.HTTP_OK) {
                InputStream inputStream = connection.getInputStream();
                BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream));
                StringBuilder stringBuilder = new StringBuilder();
                String line;
                while ((line = reader.readLine()) != null) {
                    stringBuilder.append(line);
                }
                responseString = stringBuilder.toString();
            } else {
                responseString = "Error fetching data";
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    protected void onPostExecute(Void result) {
        super.onPostExecute(result);

        if (responseString != null && !responseString.isEmpty()) {
            try {
                JSONObject responseObject = new JSONObject(responseString);

                boolean error = responseObject.getBoolean("error");
                if (!error) {
                    JSONArray vehiclesArray = responseObject.getJSONArray("vehicles");

                    vehicles = new ArrayList<>();
                    for (int i = 0; i < vehiclesArray.length(); i++) {
                        JSONObject vehicleObject = vehiclesArray.getJSONObject(i);
                        int id = vehicleObject.getInt("id");
                        String manufacturer = vehicleObject.getString("manufacturer");
                        String model = vehicleObject.getString("model");
                        int year = vehicleObject.getInt("year");
                        double price = vehicleObject.getDouble("price");
                        String color = vehicleObject.getString("color");
                        int mileage = vehicleObject.getInt("mileage");
                        String fuelType = vehicleObject.getString("fuel_type");
                        String description = vehicleObject.getString("description");
                        String image = vehicleObject.getString("image");

                        Vehicle vehicle = new Vehicle(id, manufacturer, model, year, price, color, mileage, fuelType, description, image);
                        vehicles.add(vehicle);
                    }

                    addHeaders();
                    addRows();
                } else {
                    String message = responseObject.getString("message");
                    // display error message
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        } else {
            // display error message
        }
    }
}

}

<!-- =============================================================================== -->
<!-- ============================= Java Class "Vehicle" code ============================= -->
<!-- =============================================================================== -->

public class Vehicle {
    private int id;
    private String manufacturer;
    private String model;
    private int year;
    private double price;
    private String color;
    private int mileage;
    private String fuelType;
    private String description;
    private String image;

    public Vehicle(int id, String manufacturer, String model, int year, double price, String color, int mileage, String fuelType, String description, String image) {
        this.id = id;
        this.manufacturer = manufacturer;
        this.model = model;
        this.year = year;
        this.price = price;
        this.color = color;
        this.mileage = mileage;
        this.fuelType = fuelType;
        this.description = description;
        this.image = image;
    }

    public int getId() {
        return id;
    }

    public String getManufacturer() {
        return manufacturer;
    }

    public String getModel() {
        return model;
    }

    public int getYear() {
        return year;
    }

    public double getPrice() {
        return price;
    }

    public String getColor() {
        return color;
    }

    public int getMileage() {
        return mileage;
    }

    public String getFuelType() {
        return fuelType;
    }

    public String getDescription() {
        return description;
    }

    public String getImage() {
        return image;
    }
}
