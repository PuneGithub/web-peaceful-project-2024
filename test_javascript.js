const balance = document.getElementById('balance')
const money_plus = document.getElementById('money-plus')
const money_minus = document.getElementById('money-minus')
const list = document.getElementById('list')
const form = document.getElementById('form')
const text = document.getElementById('text')
const amount = document.getElementById('amount')


let transactions = []

const init = () => {
    list.innerHTML = ''
    transactions.forEach(addDataToList)
    calMoney()
}

const addDataToList = (transactions) => {
    const symbol = transactions.amount < 0 ?'-':'+'
    const status = transactions.amount < 0 ?'minus':'plus'
    
    const item = document.createElement('li')
    result = formatNumber(Math.abs(transactions.amount))
    item.classList.add(status)
    item.innerHTML = `${transactions.text} <span>${symbol}${result}</span><button class="delete-btn" onclick="removeData(${transactions.id})">X</button>`
    list.appendChild(item)
}

const formatNumber = (num) => {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

const autoId = () => {
    return Math.floor(Math.random()*1000000)
}

const calMoney = () => {
    const amounts = transactions.map(transactions=>transactions.amount)
    const total = amounts.reduce((result,item)=>(result+=item),0).toFixed(2)
    
    //รายรับ
    const income = amounts.filter(item=>item>0).reduce((result,item)=>(result+=item),0).toFixed(2)
    //รายจ่าย
    const expense = amounts.filter(item=>item<0).reduce((result,item)=>(result+=item),0).toFixed(2)

    //display
    balance.innerText = `฿`+formatNumber(total)
    money_plus.innerText = `฿`+formatNumber(income)
    money_minus.innerText = `฿`+formatNumber(expense)
}

const removeData = (id) => {
    transactions = transactions.filter(t => t.id !== id);
    init()
}

const addTransaction = (e) =>{
    e.preventDefault()
    if (text.value.trim() === '' || amount.value.trim() === '') {
        alert("โปรดป้อนข้อมูลให้ครบ")
    } else {
        const data ={
            id:autoId(),
            text:text.value,
            amount:+amount.value
        }
        transactions.push(data)
        addDataToList(data)
        calMoney()
        text.value=''
        amount.value=''
    }
}

form.addEventListener('submit',addTransaction)

calMoney()
init()
