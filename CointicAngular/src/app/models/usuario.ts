export class Usuario {
    private _idUsuario: number;
    private _nombreUsuario: string;
    private _apellidoPaterno: string;
    private _apellidoMaterno: string;
    private _nickName: string;
    private _correoDestino: string;
    private _idDepartamento: number;
    private _nombreDepartamento: string;
    private _idPerfil: number;
    private _nombrePerfil: string;
    private _Status: number;
    private _fotoUsuario: string;
    
    constructor(values: any) {
        this._idUsuario = values['idUsuario'] || 0;
        this._nombreUsuario = values['nombreUsuario'] || '';
        this._apellidoPaterno = values['apellidoPaterno'] || '';
        this._apellidoMaterno = values['apellidoMaterno'] || '';
        this._correoDestino = values['correoDestino'] || '';
        this._nickName = values['nickName'] || '';
        this._idDepartamento = values['idDepartamento'] || 0;
        this._nombreDepartamento = values['nombreDepartamento'] || '';
        this._idPerfil = values['idPerfil'] || 0;
        this._nombrePerfil = values['nombrePerfil'] || '';
        this._Status = values['Status'] || 0;
        this._fotoUsuario = values['fotoUsuario'] || '';       
    }

    get idUsuario(): number {
        return this._idUsuario;
    }

    set idUsuario(value: number) {
        this._idUsuario = value;
    }
    get nombreUsuario(): string {
        return this._nombreUsuario;
    }

    set nombreUsuario(value: string) {
        this._nombreUsuario = value;
    }
    get apellidoPaterno(): string {
        return this._apellidoPaterno;
    }

    set apellidoPaterno(value: string) {
        this._apellidoPaterno = value;
    }
    get apellidoMaterno(): string {
        return this._apellidoMaterno;
    }

    set apellidoMaterno(value: string) {
        this._apellidoMaterno = value;
    }
    get correoDestino(): string {
        return this._correoDestino;
    }

    set correoDestino(value: string) {
        this._correoDestino = value;
    }
    get nickName(): string {
        return this._nickName;
    }

    set nickName(value: string) {
        this._nickName = value;
    }
    get idDepartamento(): number {
        return this._idDepartamento;
    }

    set idDepartamento(value: number) {
        this._idDepartamento = value;
    }
    get nombreDepartamento(): string {
        return this._nombreDepartamento;
    }

    set nombreDepartamento(value: string) {
        this._nombreDepartamento = value;
    }
    
    get idPerfil(): number {
        return this._idPerfil;
    }

    set idPerfil(value: number) {
        this._idPerfil = value;
    }
    get nombrePerfil(): string {
        return this._nombrePerfil;
    }

    set nombrePerfil(value: string) {
        this._nombrePerfil = value;
    }
    get Status(): number {
        return this._Status;
    }
    set Status(value: number) {
        this._Status = value;
    }
    get fotoUsuario(): string {
        return this._fotoUsuario;
    }
    set fotoUsuario(value: string) {
        this._fotoUsuario = value;
    }
}

