<?php

    namespace App\Models;

    use MF\Model\Model;

     class Usuario extends Model{

            //atributos de colunas que tem no bd
            private $id;
            private $nome;
            private $email;
            private $senha;
            
            //manipular dados
            public function __set($atributo, $valor){
                $this->$atributo = $valor;
            }

            //recuperar dados
            public function __get($atributo){
                return $this->$atributo;
            }

            //salvar dados no banco
            public function salvar(){
                
                $query = "insert into usuarios (nome, email, senha)values(:nome, :email, :senha)";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':nome',$this->__get('nome'));
                $stmt->bindValue(':email',$this->__get('email'));
                $stmt->bindValue(':senha',$this->__get('senha'));
                $stmt->execute();

                return $this;
            }

            //valida se os campos do cadastro estão vazios 
            public function validaCamposCadastro(){
                $valido =true;

                if(strlen($this->__get('nome'))<3){
                    $valido =false;
                }
                if(strlen($this->__get('email'))<3){
                    $valido =false;
                }
                if(strlen($this->__get('senha'))<3){
                    $valido =false;
                }

                return $valido;
                 
            }


            public function validaDuplicidadeCadastro(){
                $query = "select email from usuarios where email = :email";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(":email", $this->__get('email'));
                $stmt->execute();

                return $stmt->fetchAll(\PDO::FETCH_ASSOC);

            }

            public function autenticar(){
                $query = "select id, nome, email,senha from usuarios where email = :email and senha = :senha";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(":email", $this->__get('email'));
                $stmt->bindValue(":senha", $this->__get('senha'));
                $stmt->execute();

                $autenticacaoUsuario = $stmt->fetch(\PDO::FETCH_ASSOC); //deve retornar apenas 1 registro

                
                if($autenticacaoUsuario['id'] != '' && $autenticacaoUsuario['nome'] != '' ) {

                    $this->__set('id', $autenticacaoUsuario['id']); //seta o id no objeto
                    $this->__set('nome', $autenticacaoUsuario['nome']); //seta o nome no objeto
                }

                return $this; //retorna o proprio objeto
        }

            //pesquisa quem quer seguir
            public function getAll(){

                $query = "select 
                                u.id, u.nome, u.email,

                                (select count(*) 
                                from usuarios_seguidores us 
                                where us.id_usuario = :id_usuario and id_usuario_seguindo = u.id) as seguindo_sn

                         from usuarios u
                         where 
                         u.nome  like  :nome 
                         and u.id != :id_usuario ";
                         
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(":nome", '%' .$this->__get('nome') . '%');
                $stmt->bindValue(":id_usuario", $this->__get('id'));
        
                $stmt->execute();

                $registro_retorno = $stmt->fetchAll(\PDO::FETCH_ASSOC); //deve retornar registros

                return $registro_retorno;

            }


            //seguir usuario (poderia criar um novo controlador para atender a essa demanda)
            public function seguirUsuario($id_usuario_seguindo){

                $query = "insert into usuarios_seguidores (id_usuario,id_usuario_seguindo) values (:id_usuario, :id_usuario_seguindo)";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(":id_usuario", $this->__get('id'));
                $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo); //pega o valor do parametro
                $stmt->execute();   
                return true;
            
            }

            //Deixar de seguir usuario (poderia criar um novo controlador para atender a essa demanda)
            public function deixarSeguirUsuario($id_usuario_seguindo){


                $query = "delete 
                          from  usuarios_seguidores 
                          where  
                                id_usuario = :id_usuario 
                                and id_usuario_seguindo = :id_usuario_seguindo";

                $stmt = $this->db->prepare($query);
                $stmt->bindValue(":id_usuario", $this->__get('id'));
                $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo); //pega o valor do parametro
                $stmt->execute();   
                return true;
            
                

            }

            //informação do usuário na timeline
            public function getInfoUsuario(){

                $query = "select 
                                u.id, u.nome, u.email,

                                (select count(*)
                                from usuarios_seguidores us 
                                where us.id_usuario = u.id) as qt_seguindo,

                                (select count(*) 
                                from usuarios_seguidores us 
                                where us.id_usuario_seguindo = u.id) as qt_seguidores,

                                (select count(*) 
                                from tweets t
                                where t.id_usuario = u.id) as qt_tweets

                         from usuarios u
                         where u.id = :id_usuario ";
                         
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(":id_usuario", $this->__get('id'));
        
                $stmt->execute();

                $retorno_info = $stmt->fetch(\PDO::FETCH_ASSOC); //deve retornar registros

                return $retorno_info;


            }

    }


?>
