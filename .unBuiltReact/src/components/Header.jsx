import logo from "./image.png"
export function Header({ButtonClick}){
    return(
        <header>
            <img src = {logo} alt = ""/>
            <h1>teehee</h1>
            <button onClick = {ButtonClick}>teehee</button>
        </header>
        
    )
}
