export default class Gate{

    constructor(user) {
        this.user = user;
    }

    isAdmin(){
        return this.user.isAdmin === 1;
    }
    isSeller(){
        return this.user.isSeller === 0;
    }
}
