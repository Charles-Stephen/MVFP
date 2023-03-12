<?php

include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dealer_id = $_POST["dealer_id"];
    $user_id = $_POST["user_id"];
    $vehicle_model = $_POST["vehicle_model"];
    $loan_amount = $_POST["loan_amount"];
    $down_payment = $_POST["down_payment"];
    $term = $_POST["term"];
    $interest_rate = $_POST["interest_rate"];
    $monthly_payment = $_POST["monthly_payment"];

    $insert_query = "INSERT INTO `finance_applications`(`dealer_id`, `user_id`, `vehicle_model`, `loan_amount`, `down_payment`, `term`, `interest_rate`, `monthly_payment`, `approval_status`) 
                     VALUES ('$dealer_id', '$user_id', '$vehicle_model', '$loan_amount', '$down_payment', '$term', '$interest_rate', '$monthly_payment', 'Pending')";

    if (mysqli_query($conn, $insert_query)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }
} else {
    echo "no request";
}
mysqli_close($conn);
?>

<!-- =============================================================================== -->
<!-- ============================= Gradel Dependicies ============================= -->
<!-- =============================================================================== -->
implementation 'com.android.volley:volley:1.2.0'




<!-- =============================================================================== -->
<!-- ================================= Layout code ================================= -->
<!-- =============================================================================== -->

<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout
    xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <Spinner
        android:id="@+id/vehicle_model_spinner"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_margin="16dp" />

    <EditText
        android:id="@+id/loan_amount_edit_text"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_below="@+id/vehicle_model_spinner"
        android:layout_margin="16dp"
        android:hint="Loan Amount"
        android:inputType="numberDecimal" />

    <EditText
        android:id="@+id/down_payment_edit_text"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_below="@+id/loan_amount_edit_text"
        android:layout_margin="16dp"
        android:hint="Down Payment"
        android:inputType="numberDecimal" />

    <EditText
        android:id="@+id/term_edit_text"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_below="@+id/down_payment_edit_text"
        android:layout_margin="16dp"
        android:hint="Term"
        android:inputType="number" />

    <EditText
        android:id="@+id/interest_rate_edit_text"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_below="@+id/term_edit_text"
        android:layout_margin="16dp"
        android:hint="Interest Rate"
        android:inputType="numberDecimal" />

    <Button
        android:id="@+id/submit_button"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_below="@+id/interest_rate_edit_text"
        android:layout_margin="16dp"
        android:text="Submit" />

</RelativeLayout>




<!-- =============================================================================== -->
<!-- ================================= Layout code ================================= -->
<!-- =============================================================================== -->


import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;

public class MainActivity extends AppCompatActivity {

    private Spinner vehicleModelSpinner;
    private EditText loanAmountEditText, downPaymentEditText, termEditText, interestRateEditText;
    private Button submitButton;
    private String selectedVehicleModel;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // initialize views
        vehicleModelSpinner = findViewById(R.id.vehicle_model_spinner);
        loanAmountEditText = findViewById(R.id.loan_amount_edit_text);
        downPaymentEditText = findViewById(R.id.down_payment_edit_text);
        termEditText = findViewById(R.id.term_edit_text);
        interestRateEditText = findViewById(R.id.interest_rate_edit_text);
        submitButton = findViewById(R.id.submit_button);

        // get spinner choices from API and set up spinner
        getSpinnerChoices();

        // set up submit button click listener
        submitButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
            // get user input from EditTexts
            String loanAmount = loanAmountEditText.getText().toString().trim();
            String downPayment = downPaymentEditText.getText().toString().trim();
            String term = termEditText.getText().toString().trim();
            String interestRate = interestRateEditText.getText().toString().trim();
            // validate user input
            if (loanAmount.isEmpty() || downPayment.isEmpty() || term.isEmpty() || interestRate.isEmpty()) {
                Toast.makeText(MainActivity.this, "Please fill all fields", Toast.LENGTH_SHORT).show();
            } else {
                // make API call to insert data into MySQL database
                insertData(selectedVehicleModel, loanAmount, downPayment, term, interestRate);
            }
        }
    });
}

// method to get spinner choices from API and set up spinner
private void getSpinnerChoices() {
    OkHttpClient client = new OkHttpClient();
    Request request = new Request.Builder()
            .url("http://example.com/api/get_vehicle_models.php")
            .build();

    client.newCall(request).enqueue(new Callback() {
        @Override
        public void onFailure(Call call, IOException e) {
            e.printStackTrace();
        }

        @Override
        public void onResponse(Call call, Response response) throws IOException {
            if (response.isSuccessful()) {
                final String responseData = response.body().string();
                MainActivity.this.runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        // parse JSON response and set up spinner
                        List<String> vehicleModels = new ArrayList<>();
                        try {
                            JSONArray jsonArray = new JSONArray(responseData);
                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject jsonObject = jsonArray.getJSONObject(i);
                                String model = jsonObject.getString("model");
                                vehicleModels.add(model);
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                        ArrayAdapter<String> adapter = new ArrayAdapter<>(MainActivity.this,
                                android.R.layout.simple_spinner_item, vehicleModels);
                        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                        vehicleModelSpinner.setAdapter(adapter);
                    }
                });
            }
        }
    });
}

// method to insert data into MySQL database
private void insertData(String vehicleModel, String loanAmount, String downPayment, String term, String interestRate) {
    OkHttpClient client = new OkHttpClient();
    Request request = new Request.Builder()
            .url("http://example.com/api/insert_data.php?vehicle_model=" + vehicleModel +
                    "&loan_amount=" + loanAmount +
                    "&down_payment=" + downPayment +
                    "&term=" + term +
                    "&interest_rate=" + interestRate)
            .post(null)
            .build();

    client.newCall(request).enqueue(new Callback() {
        @Override
        public void onFailure(Call call, IOException e) {
            e.printStackTrace();
        }

        @Override
        public void onResponse(Call call, Response response) throws IOException {
            if (response.isSuccessful()) {
                final String responseData = response.body().string();
                MainActivity.this.runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if (responseData.equals("success")) {
                            Toast.makeText(MainActivity.this, "Data inserted successfully", Toast.LENGTH_SHORT).show();
                            // clear EditTexts
                            loanAmountEditText.setText("");
                            downPaymentEditText.setText("");
                            termEditText.setText("");
                            interestRateEditText.setText("");
                        } else {
                            Toast.makeText(MainActivity.this, "Error inserting data", Toast.LENGTH_SHORT).show();
                        }
                    }
                });
            }
        }
    });
}
    
        
        }