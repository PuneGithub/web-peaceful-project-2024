// const dataContainer = document.getElementById("data-container");

// fetch("https://jsonplaceholder.typicode.com/posts")
//     //แปลงข้อมูลจาก json ให้เป็น Javascript object ด้วย method .json()
//     .then(response => response.json())
//     .then(data => {
//         data.forEach(post => {
//             console.log(post);
//             const postElement = document.createElement("p");
//             postElement.textContent = `
//                 Post ID: ${post.id} - Title ${post.title}, Body: ${post.body}
//             `;
//             dataContainer.appendChild(postElement);
//         });
//     })
//     .catch(error => {
//         console.log("Error", error);
//     })