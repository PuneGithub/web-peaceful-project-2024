// function updateActionUser(postId){
//     console.log(postId)

//     fetch('/web_peaceful_project_2024/system/loveButton.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({postId: postId})
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error('Network response was not Ok')
//         }
//         return response.json()
//     })
//     .then(data => {
//         const loveCount = document.getElementById('loveCount');

//         const loveButton = document.getElementById('loveButton');

//         loveCount.textContent = data.loveCount;

//         loveButton.onclick = null


//     })
//     .catch(error => {
//         console.error('Error', error)
//     })
// }

function loveButton(postId)
{
    fetch("web_peaceful_project_2024/system/loveButton.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `postId=${postId}`,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            document.getElementById(`loveCount-${postId}`).textContent = data.loveCount;
        } else {
            alert(data.message);
        }
    })
    .catch((error) => {
        console.error("Error:", error);
    })
}
