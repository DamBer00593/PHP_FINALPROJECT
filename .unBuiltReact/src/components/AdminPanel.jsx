import { useState } from "react";
export function AdminPanel({PermissionLevel, callStuff, createTable}){
    let [selectedOperation, setSelectedOperation] = useState(null);
    async function senpai(){
        let operation = selectedOperation;

        if(operation === "team"){
            let resp = callStuff.getItems("team")
            let test = resp.data;
            console.log(test)
            console.log(resp.data.length)
        } else if (operation === "player"){

        }
    }

    return(
        <div>
            <button onClick = {() => setSelectedOperation("team")}>Team Operations</button>
            <button onClick = {() => setSelectedOperation("player")}>Player Operations</button>

            <div className="bodySomethingorother">
                {(selectedOperation?createTable(selectedOperation):"")}
            </div>
        </div>
    )
}