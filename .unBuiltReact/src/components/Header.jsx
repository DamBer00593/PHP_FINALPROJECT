import logo from "../image.png"
export function Header({ButtonClick, PermissionLevel, processLoginClick,goToAdminClick}){
    return(
        <header>
            <img src = {logo} alt = ""/>
            <h1>Bulleye Bowling</h1>
            <button onClick = {ButtonClick}>Login</button>
            <button onClick = {ButtonClick}>Login</button>
            
            <button onClick = {ButtonClick}>Login</button>
            {(PermissionLevel == "admin")?<button onClick = {goToAdminClick}>Admin Page</button>:""}
            <button onClick = {processLoginClick}>Login</button>
        </header>
        
    )
}