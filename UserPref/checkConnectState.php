<?php
//에러 리포팅
ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../db_connect.php'; // 데이터베이스 연결

$userId = $_GET['userId'];

// 유저 프로필 가져오기
$sql = "SELECT * FROM userAuth WHERE user_id = :userId";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$userAuth = $stmt->fetch(PDO::FETCH_ASSOC);

/// 파트너 프로필 가져오기
$sql2 = "SELECT partner_id FROM userAuth WHERE user_id = :userId";
$stmt = $conn->prepare($sql2);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$partnerId = $stmt->fetchColumn();

$partnerAuth = null;
if ($partnerId) {
    $sql3 = "SELECT * FROM userAuth WHERE user_id = :partnerId";
    $stmt = $conn->prepare($sql3);
    $stmt->bindParam(':partnerId', $partnerId, PDO::PARAM_INT);
    $stmt->execute();
    $partnerAuth = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 클라이언트에게 JSON 형태로 응답
header('Content-Type: application/json');

if ($userAuth && $partnerAuth) {
    echo json_encode([
        'success' => true,
        'userState' => $userAuth['connect'],
        'partnerState' => $partnerAuth['connect']

    ]);


}else {
    // 프로필이 존재하지 않는 경우
    echo json_encode([
        'success' => false,
        'message' => '프로필이 존재하지 않습니다. 쉐어드에 userId가 존재하는지 확인하세요'
    ]);
}


?>