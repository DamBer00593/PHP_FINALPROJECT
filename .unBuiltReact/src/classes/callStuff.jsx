export class callStuff{
    version = "v1"
    url = "https://api.bulleye.party/api/"+this.version+"/"
    async getItems(item){
        let stuffs = {
            method: "GET",
            mode: "cors",
            headers: {
                "Authorization":"Bearer 8RbYFydpaHA3G*sja4AL7Hkum!5f4tp5"
            }
        }
        let things = await fetch(this.url+item, stuffs);
        let thing = await things.json();
        return thing;
        
    }
    async getOne(item, id){
        let stuffs = {
            method: "GET",
            mode: "cors",
            headers: {
                "Authorization":"Bearer 8RbYFydpaHA3G*sja4AL7Hkum!5f4tp5"
            }
        }
        let things = await fetch(this.url+item+"/"+id, stuffs);
        let thing = await things.json();
        return thing;
        
    }
    async createItem(item, ob){
        let stuffs = {
            method: "POST",
            mode: "cors",
            headers: {
                "Authorization":"Bearer 8RbYFydpaHA3G*sja4AL7Hkum!5f4tp5"
            },
            body: ob
        }
        let things = await fetch(this.url+item, stuffs);
        let thing = await things.json();
        return thing;
        
    }
    async deleteItem(item, ob){
        let stuffs = {
            method: "DELETE",
            mode: "cors",
            headers: {
                "Authorization":"Bearer 8RbYFydpaHA3G*sja4AL7Hkum!5f4tp5"
            },
            body: ob
        }
        let things = await fetch(this.url+item, stuffs);
        let thing = await things.json();
        return thing;
        
    }
    async updateItem(item, ob){
        let stuffs = {
            method: "PUT",
            mode: "cors",
            headers: {
                "Authorization":"Bearer 8RbYFydpaHA3G*sja4AL7Hkum!5f4tp5"
            },
            body: ob
        }
        let things = await fetch(this.url+item, stuffs);
        let thing = await things.json();
        return thing;
        
    }
}

export default callStuff