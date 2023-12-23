
async function loginCheck(user){
    let stuffs = {
        method: "POST",
        mode: "cors",
        headers: {
            "Authorization":"Bearer 8RbYFydpaHA3G*sja4AL7Hkum!5f4tp5"
        },
        body: user
    }
    let things = await fetch("https://api.bulleye.party/api/v1/auth/user/login/"+user.userEmail, stuffs);
    let thing = await things.json();
    return thing;
}

export default loginCheck