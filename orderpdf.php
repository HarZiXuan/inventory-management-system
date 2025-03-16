<?php
session_start();
require_once "config.php";
require_once "fpdf/fpdf186/fpdf.php";



$companyId = 1; 
$query = "SELECT * FROM companyprofile WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $companyId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$companyProfile = mysqli_fetch_assoc($result);
$companyProfileImagePath = $companyProfile['profilepicture'];
$companyName = $companyProfile['name'];

$selectedLocation = mysqli_real_escape_string($conn, $_POST['locationFilter']);
 $date1 = date("Y-m-d", strtotime($_POST['date1']));
    $date2 = date("Y-m-d", strtotime($_POST['date2']));

$pdf= new FPDF('P','mm',"A4");
$pdf->AddPage();
$pdf->SetFont('Arial','B', 20);

if (!empty($companyProfileImagePath)) {
    // Set the position for the logo
    $pdf->SetXY(10, $pdf->GetY());
    
    // Add the logo to the PDF
    $imageX = $pdf->GetPageWidth() - 120; // Adjust as needed

    // Set the position for the logo
    $pdf->SetXY($imageX, $pdf->GetY());
    
    // Add the logo to the PDF
    $pdf->Image($companyProfileImagePath, $pdf->GetX(), $pdf->GetY(), 30);
    
    // Move the position after adding the logo
    $pdf->SetXY($pdf->GetX() + 30, $pdf->GetY());
    
    // Set the position for the text
    $pdf->SetXY($imageX, $pdf->GetY());
    
    // Move the position after adding the logo
    $pdf->SetXY($pdf->GetX() + 30, $pdf->GetY());
} else {
    // If the logo path is empty, you can display a placeholder image
    $pdf->Cell(30, 0, ''); // Empty cell as a placeholder
}
$pdf->Cell(59 ,20,'',0,1);

$pdf->Cell(190, 10, 'Product Transfer', 1, 0, 'C');
$pdf->Cell(71,5,'',0,0);
$pdf->Cell(59 ,10,'',0,1);

$pdf->SetFont('Arial','', 13);

$pdf->Cell(59 ,5,'',0,1);

$pdf->Cell(59, 5, 'Company Name : ' . $companyName, 0, 0);
$pdf->Cell(75 ,7,'',0,);

$pdf->Cell(10, 5, 'Received by: ', 0, 0);

$pdf->Cell(59 ,7,'',0,1);

$pdf->Cell(59 ,5,'Location             : ' . $selectedLocation,0,0);
$pdf->Cell(75 ,5,'',0,0);

$pdf->Cell(150, 5, 'Received date: ', 0, 0);

$pdf->Cell(59 ,7,'',0,1);

$pdf->Cell(59 ,5,'Tel No                : 016-7195325' ,0,0);
$pdf->Cell(59 ,7,'',0,1);



if (!empty($_POST['date1']) && !empty($_POST['date2'])) {
    $dateText = 'Date                   : ' . $date1 . ' to ' . $date2;
} else {
    // If date1 and date2 are not selected, retrieve oldest and newest dates
    $dateRangeQuery = "SELECT MIN(DATE_FORMAT(addeddate, '%Y-%m-%d')) AS oldestDate, MAX(DATE_FORMAT(addeddate, '%Y-%m-%d')) AS newestDate FROM deliverorder";
    $dateRangeResult = mysqli_query($conn, $dateRangeQuery);
    $dateRangeData = mysqli_fetch_assoc($dateRangeResult);
    
    $oldestDate = $dateRangeData['oldestDate'];
    $newestDate = $dateRangeData['newestDate'];
    
    $dateText = 'Date                   : ' . $oldestDate . ' to ' . $newestDate;
}

$pdf->Cell(59, 5, $dateText, 0, 0);
$pdf->Cell(59, 5, '', 0, 1);

$pdf->SetFont('Arial','B', 11);
$pdf->Cell(59 ,5,'',0,1);

$pdf->Cell(30 ,6,'Tracking ID' ,1,0,'C');
$pdf->Cell(25 ,6,'Product ID' ,1,0,'C');
$pdf->Cell(35 ,6,'Product Name' ,1,0,'C');
$pdf->Cell(20 ,6,'Quantity' ,1,0,'C');
$pdf->Cell(20 ,6,'Unit Price' ,1,0,'C');
$pdf->Cell(30 ,6,'Location' ,1,0,'C');
$pdf->Cell(30 ,6,'Transfered Date' ,1,1,'C');

$pdf->SetFont('Arial','', 10);

// Check if date1 and date2 have values
if (!empty($_POST['date1']) && !empty($_POST['date2'])) {
   

    if (empty($_POST['locationFilter'])) {
        $reportQuery = mysqli_query($conn, "SELECT * FROM deliverorder WHERE STR_TO_DATE(`addeddate`, '%Y-%m-%d') BETWEEN '$date1' AND '$date2' ORDER BY `id` DESC") or die(mysqli_error($conn));
    } else {
        
        $reportQuery = mysqli_query($conn, "SELECT * FROM deliverorder WHERE STR_TO_DATE(`addeddate`, '%Y-%m-%d') BETWEEN '$date1' AND '$date2' AND location = '$selectedLocation' ORDER BY `id` DESC") or die(mysqli_error($conn));
    }

    while ($reportRow = mysqli_fetch_assoc($reportQuery)) {
        $pdf->Cell(30, 6, $reportRow['id'], 1, 0, 'C');
        $pdf->Cell(25, 6, $reportRow['productid'], 1, 0, 'C');
        $pdf->Cell(35, 6, $reportRow['name'], 1, 0, 'C');
        $pdf->Cell(20, 6, $reportRow['quantity'], 1, 0, 'C');
        $pdf->Cell(20, 6, $reportRow['unitprice'], 1, 0, 'C');
        $pdf->Cell(30, 6, $reportRow['location'], 1, 0, 'C');
        $pdf->Cell(30, 6, date('Y-m-d', strtotime($reportRow['addeddate'])), 1, 0, 'C');
        // ... other cells ...
        $pdf->Ln();
    }
} else {
    // Fetch all data if date1 and date2 are not set
    if (empty($_POST['locationFilter'])) {
        $reportQuery = mysqli_query($conn, "SELECT * FROM deliverorder ORDER BY `id` ASC") or die(mysqli_error($conn));
    } else {
        $selectedLocation = mysqli_real_escape_string($conn, $_POST['locationFilter']);
        $reportQuery = mysqli_query($conn, "SELECT * FROM deliverorder WHERE location = '$selectedLocation' ORDER BY `id` ASC") or die(mysqli_error($conn));
    }
    
    while ($reportRow = mysqli_fetch_assoc($reportQuery)) {
        $pdf->Cell(30, 6, $reportRow['id'], 1, 0, 'C');
        $pdf->Cell(25, 6, $reportRow['productid'], 1, 0, 'C');
        $pdf->Cell(35, 6, $reportRow['name'], 1, 0, 'C');
        $pdf->Cell(20, 6, $reportRow['quantity'], 1, 0, 'C');
        $pdf->Cell(20, 6, $reportRow['unitprice'], 1, 0, 'C');
        $pdf->Cell(30, 6, $reportRow['location'], 1, 0, 'C');
        $pdf->Cell(30, 6, date('Y-m-d', strtotime($reportRow['addeddate'])), 1, 0, 'C');
        // ... other cells ...
        $pdf->Ln();
    }
}

$pdf->SetFont('Arial','B', 14);
$pdf->Ln(30);
$pdf->Cell(50, 5, 'Authorised Signature(s)', 0, 0);
$pdf->SetFont('Arial','', 12);
$pdf->Ln(20);

$pdf->Line(10, $pdf->GetY(), 60, $pdf->GetY()); // Line for Authorised Signature(s)
$pdf->Line(150, $pdf->GetY(), $pdf->GetPageWidth() - 10, $pdf->GetY()); // Line for Received by
$pdf->Ln(); // Move to the next line

// HQ
$pdf->Cell(59, 5, 'HQ', 0, 0);
$pdf->Cell(80, 5, '', 0, 0); // Add some space between the two sections
$pdf->Cell(50, 5, 'Received by', 0, 1);

$pdf->Output();
header("Location: Order.php"); // Replace Order.php with the desired page
exit();

// Add JavaScript to prevent form resubmission
echo '<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>';
?>

