async function taskSearch(input){
}

async function callMethodAsync(method,params = []){
    return new Promise((resolve,reject) => {
        arResult = []
        BX24.callMethod(method,params,(res)=>{
            arResult = arResult.concat(arResult,res.data())
            if(res.more()){
                res.next()
            }
            else{
                resolve(arResult)
            }
        })
    })
}
async function callMethodAsyncTasks(method,params = []){
    return new Promise((resolve,reject) => {
        arResult = []
        BX24.callMethod(method,params,(res)=>{
            arResult = arResult.concat(res.data().tasks)
            if(res.more()){
                res.next()
            }
            else{
                resolve(arResult)
            }
        })
    })
}