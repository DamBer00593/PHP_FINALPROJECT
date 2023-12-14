import './App.css';
// eslint-disable-next-line
import { Login } from "./components/Login"
import { Header } from './components/Header';
import callStuff  from "./classes/callStuff"
import { useState } from "react";
import { AdminPanel } from './components/AdminPanel';
const cs = new callStuff();
function App() {
  let [permissionLevel, setPermissionLevel] = useState("guest")
  let [currentPage,setCurrentPage] = useState("home")
  // eslint-disable-next-line
  async function testClick(){
      console.log(await cs.getItems("team"))
  }
  async function handleLoginClick(){
    setPermissionLevel("admin")
  }
  function createTable(selectedOperation){
    let operation = selectedOperation;

        if(operation === "team"){
            let resp = callStuff.getItems("team")
            let test = resp.data;
            console.log(test)
            console.log(resp.data.length)
        } else if (operation === "player"){

        }
  }

  return (
    <div className="App">
      {<Header
        ButtonClick = {testClick}
        PermissionLevel = {permissionLevel}
        processLoginClick = {handleLoginClick}
        goToAdminClick = {() => setCurrentPage("admin")}
      />}
        {(currentPage === "admin")?
        <AdminPanel
        PermissionLevel = {permissionLevel}
        callStuff = {cs}
        createTable = {createTable}
        />:""
        }
    </div>
  );
}

export default App;
