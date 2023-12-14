import { useState } from "react";
export function AdminPanel({PermissionLevel, callStuff}){
    let [selectedOperation, setSelectedOperation] = useState(null);
    function senpai(){
        let operation = selectedOperation;

        if(operation == "team"){
            
        } else if (operation = "player"){

        }
    }

    return(
        <div>
            <button onClick = {() => setSelectedOperation("team")}>Team Operations</button>
            <button onClick = {() => setSelectedOperation("player")}>Player Operations</button>

            <div className="bodySomethingorother">
                {(selectedOperation?senpai():"")}
            </div>
        </div>
    )
}