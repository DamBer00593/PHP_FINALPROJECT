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


  return (
    <div className="App">
      {<Header
        ButtonClick = {testClick}
        PermissionLevel = {permissionLevel}
        processLoginClick = {handleLoginClick}
        goToAdminClick = {() => setCurrentPage("admin")}
      />}
        {(currentPage == "admin")?<AdminPanel/>:""}
    </div>
  );
}

export default App;
